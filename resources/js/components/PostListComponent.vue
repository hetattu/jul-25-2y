<template>
  <div class="container">
    <div class="row justify-content-center" v-for="post in posts" v-bind:key="post.id">
      <div class="col-md-8">
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
        posts: []
      }
    },
    methods: {
      getPosts() {
        axios.get('/api/posts')
          .then((res) => {
            this.posts = res.data;
          });
      }
    },
    mounted() {
      this.getPosts();
    }
  }
</script>
