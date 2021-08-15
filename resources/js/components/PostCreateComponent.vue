<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-sm-2">
        <div class="card" v-for="tag in tags" v-bind:key="tag.id">
          <div v-if="!createForm.tags.includes(tag.id)" class="btn btn-outline-primary" v-bind:style="{'color': tag.color_code}" v-on:click="switchTag(tag.id)">
            {{ tag.name }}
          </div>
          <div v-else class="btn" v-bind:style="{'background-color': tag.color_code}" v-on:click="switchTag(tag.id)">
            {{ tag.name }}
          </div>
        </div>
      </div>
      <div class="col-sm-8">
        <span v-for="tagId in createForm.tags" v-bind:key="'tag' + tagId" v-bind:style="{'background-color': getTagColorCode(tagId)}">{{ getTagName(tagId) }}</span>

        <div class="form-group">
          <form-input
            v-bind:id="'subject'"
            v-model="createForm.subject"
            v-bind:input-type="'text'"
            v-bind:maxlength="255"
            v-bind:iclass="'col-sm-9 form-control'"
            v-bind:title="'Subject'"
          />
          <form-input
            v-bind:id="'body'"
            v-model="createForm.body"
            v-bind:input-type="'text'"
            v-bind:maxlength="255"
            v-bind:iclass="'col-sm-9 form-control'"
            v-bind:title="'Body'"
          />
        </div>

        <button class="btn btn-success" v-on:click="submit()">Create</button>
      </div>
    </div>
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
      tags: {},
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
    switchTag(tagId) {
      if (this.createForm.tags.includes(tagId)) {
        this.createForm.tags = this.createForm.tags.filter(val => val != tagId);
      } else {
        this.createForm.tags.push(tagId);
      }
    },
    getTagColorCode(tagId) {
      var tag = this.tags.find(tag => tag.id == tagId);
      return tag.color_code;
    },
    getTagName(tagId) {
      var tag = this.tags.find(tag => tag.id == tagId);
      return tag.name;
    }
  },

  mounted() {
    this.getTags();
  }
}
</script>
