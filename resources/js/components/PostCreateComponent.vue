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
    <div class="row justify-content-center">
      <div class="col-sm-2">
        <div class="card" v-for="tag in tags" :key="tag.id">
          <div v-if="!createForm.tags.includes(tag.id)" class="btn btn-outline-primary" :style="{'color': tag.color_code}" @click="switchTag(tag.id)">
            {{ tag.name }}
          </div>
          <div v-else class="btn" :style="{'background-color': tag.color_code}" @click="switchTag(tag.id)">
            {{ tag.name }}
          </div>
        </div>
      </div>
      <div class="col-sm-8">
        <transition-group name="list">
          <span v-for="tagId in createForm.tags" :key="'tag' + tagId" :style="{backgroundColor: getTagColorCode(tagId)}">{{ getTagName(tagId) }}</span>
        </transition-group>

        <div class="form-group">
          <form-input
            :id="'subject'"
            v-model="createForm.subject"
            :input-type="'text'"
            :maxlength="255"
            :iclass="'col-sm-9 form-control'"
            :title="'Subject'"
          />
          <form-input
            :id="'body'"
            v-model="createForm.body"
            :input-type="'text'"
            :maxlength="255"
            :iclass="'col-sm-9 form-control'"
            :title="'Body'"
          />
        </div>

        <button class="btn btn-success" @click.prevent="submit()">Create</button>
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
      tags: [],
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
        this.createForm.tags = this.createForm.tags.filter(val => val !== tagId);
      } else {
        this.createForm.tags.push(tagId);
      }
    },
    getTagColorCode(tagId) {
      let tag = this.tags.find(tag => tag.id === tagId);
      return tag.color_code;
    },
    getTagName(tagId) {
      let tag = this.tags.find(tag => tag.id === tagId);
      return tag.name;
    }
  },

  mounted() {
    this.getTags();
  }
}
</script>
