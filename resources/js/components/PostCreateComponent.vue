<style scoped>
  .list-enter-active, .list-leave-active {
    transition: all 0.5s;
  }
  .list-enter, .list-leave-to {
    opacity: 0;
  }
</style>

<template>
  <div class="container">
    <v-form
      ref="form"
      v-model="valid"
      lazy-validation
    >
      <v-text-field
        v-model="createForm.subject"
        :counter="100"
        :rules="subjectRules"
        label="Subject"
        required
      ></v-text-field>

      <v-textarea
        v-model="createForm.body"
        :rules="bodyRules"
        label="body"
        rows="3"
        required
      ></v-textarea>

      <v-combobox
        v-model="comboboxTags"
        :items="tags.map(tag => tag.name)"
        chips
        label="Your favorite hobbies"
        multiple
      >
        <template v-slot:selection="{ attrs, item, select, selected }">
          <v-chip
            v-bind="attrs"
            :input-value="selected"
            close
            @click="select"
            @click:close="remove(item)"
          >
            <strong>{{ item }}</strong>
          </v-chip>
        </template>
      </v-combobox>

      <v-btn
        :disabled="!valid"
        color="success"
        class="mr-4"
        @click="create"
      >
        Create
      </v-btn>
    </v-form>
  </div>
</template>

<script>
export default {

  data: function () {
    return {
      createForm: {
        subject: '',
        body: '',
        tags: [],
      },
      tags: [],
      comboboxTags: [],

      valid: true,
      subjectRules: [
        v => !!v || 'Subject is required',
        v => (v && v.length <= 100) || 'Subject must be less than 100 characters',
      ],
      bodyRules: [
        v => !!v || 'Body is required',
      ],
    }
  },
 
  methods: {
    submit() {
      axios.post('/api/posts', this.createForm).then(res => {
        this.$router.push({name: 'post.list'});
      });
    },
    getTags() {
      axios.get('/api/tags').then(res => {
        this.tags = res.data.data;
      });
    },
    getTagColorCode(tagId) {
      let tag = this.tags.find(tag => tag.id === tagId);
      return tag.color_code;
    },
    getTagName(tagId) {
      let tag = this.tags.find(tag => tag.id === tagId);
      return tag.name;
    },

    create () {
      if (this.$refs.form.validate()) {
        let selectedTags = [];
        this.comboboxTags.forEach(name => {
          selectedTags.push(this.tags.find(tag => tag.name === name));
        });
        this.createForm.tags = selectedTags.map(tag => tag.id);
        this.submit();
      }
    },
    remove (item) {
      this.createForm.tags.splice(this.createForm.tags.indexOf(item), 1)
      this.createForm.tags = [...this.createForm.tags]
    },
  },

  mounted() {
    this.getTags();
  }
}
</script>
