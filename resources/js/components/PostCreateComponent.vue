<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-sm-2">
        <div class="card" v-for="tag in tags" v-bind:key="tag.id">
          <div v-if="!selectTags.includes(tag.id)" class="btn btn-outline-primary" v-bind:style="{'color': tag.color_code}" v-on:click="switchTag(tag.id)">
            {{ tag.name }}
          </div>
          <div v-else class="btn" v-bind:style="{'background-color': tag.color_code}" v-on:click="switchTag(tag.id)">
            {{ tag.name }}
          </div>
        </div>
      </div>
      <div class="col-sm-8">
        <span v-for="tagId in selectTags" v-bind:key="'tag' + tagId" v-bind:style="{'background-color': tagsInfo[tagId].color_code}">{{ tagsInfo[tagId].name }}</span>
        <form v-on:submit.prevent="submit">
          <div class="form-group row">
            <label for="title" class="col-sm-3 col-form-label">Subject</label>
            <input type="text" class="col-sm-9 form-control" v-model="subject">
          </div>
          <div class="form-group row">
            <label for="content" class="col-sm-3 col-form-label">Body</label>
            <input type="text" class="col-sm-9 form-control" v-model="body">
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      subject: '',
      body: '',
      tags: {},
      tagsInfo: {},
      selectTags: []
    }
  },
  methods: {
    submit() {
      var post_data = {
        'subject': this.subject,
        'body': this.body,
        'tags': this.selectTags
      };
      axios.post('/api/posts', post_data).then((res) => {
        this.$router.push({name: 'post.list'});
      });
    },
    getTags() {
      axios.get('/api/tags').then((res) => {
        var addTagArray = [];
        res.data.data.forEach((tag) => {
          addTagArray[tag.id] = tag;
        });
        this.tags = res.data.data;
        this.tagsInfo = addTagArray;
      });
    },
    switchTag(tagId) {
      if (this.selectTags.includes(tagId)) {
        this.selectTags = this.selectTags.filter((val) => val != tagId);
      } else {
        this.selectTags.push(tagId);
      }
    }
  },
  mounted() {
    this.getTags();
  }
}
</script>
