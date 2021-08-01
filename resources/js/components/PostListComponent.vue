<template>
  <div class="container">
    <div class="row justify-content-center" v-for="post in posts" v-bind:key="post.id">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">{{ post.subject }}</div>

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
        posts: [
          {
            id: 1,
            subject: 'subject1',
            body: 'body1'
          },
          {
            id: 2,
            subject: 'subject2',
            body: 'body2'
          }
        ]
      }
    },
    methods: {
      getPosts() {
        axios.get('/api/posts')
          .then((res) => {
            this.posts = res.data;
            console.log('get posts.')
          });
      }
    },
    mounted() {
      console.log('Component mounted.')
      this.getPosts();
    }
  }
</script>
