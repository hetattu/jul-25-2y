<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8" v-for="post in posts" v-bind:key="post.id">
        <span v-for="tagId in post.tags" v-bind:key="'tag' + tagId" v-bind:style="{'background-color': tags[tagId].color_code}">{{ tags[tagId].name }}</span>
        <div class="card">
          <div class="card-header">
            {{ post.subject }}
            <router-link v-bind:to="{name: 'post.show', params: {postId: post.id}}">
              <button class="btn btn-primary">Show</button>
            </router-link>
          </div>

          <div class="card-body">
            {{ post.body }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        posts: [],
        tags: []
      }
    },
    methods: {
      getPosts() {
        axios.get('/api/posts').then((res) => {
          this.posts = res.data.posts;

          var addTagArray = [];
          res.data.tags.forEach((tag) => {
            addTagArray[tag.id] = tag;
          });
          this.tags = addTagArray;
        });
      }
    },
    mounted() {
      this.getPosts();
    }
  }
</script>
