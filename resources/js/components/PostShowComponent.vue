<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <form v-on:submit.prevent="submit">
          <div class="card">
            <div class="card-header">
              <input type="text" class="col-sm-9 form-control" v-model="post.subject">
            </div>

            <div class="card-body">
              <input type="text" class="col-sm-9 form-control" v-model="post.body">
            </div>
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <button class="btn btn-danger" v-on:click="deletePost">Delete</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    postId: Number
  },
  data: function () {
    return {
      post: {}
    }
  },
  methods: {
    getPost() {
      axios.get('/api/posts/' + this.postId).then((res) => {
        this.post = res.data;
      });
    },
    deletePost() {
      axios.delete('/api/posts/' + this.postId).then((res) => {
        this.$router.push({name: 'post.list'});
      });
    },
    submit() {
      var post_data = {
        'subject': this.post.subject,
        'body': this.post.body
      };
      axios.put('/api/posts/' + this.postId, this.post).then((res) => {
        this.post = res.data;
      });
    }
  },
  mounted() {
    this.getPost();
  }
}
</script>
