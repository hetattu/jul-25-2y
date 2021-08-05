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
      <div class="col-md-8">
        <form v-on:submit.prevent="addComment">
          <div class="card">
            <div class="card-header">
              <input type="text" class="col-sm-9 form-control" v-model="add_comment">
              <button type="submit" class="btn btn-success">Add</button>
            </div>
          </div>
        </form>
      </div>
      <div class="col-md-8" v-for="comment in comments" v-bind:key="comment.id">
        <div class="card">
          <div class="card-body">
            <span>{{ comment.body }}</span>
            <button class="btn btn-primary">Edit</button>
            <button class="btn btn-danger" v-on:click="deleteComment(comment.id)">Delete</button>
          </div>
        </div>
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
      post: {},
      comments: {},
      add_comment: ''
    }
  },
  methods: {
    getPost() {
      axios.get('/api/posts/' + this.postId).then((res) => {
        this.post = res.data.post;
        this.comments = res.data.comments;
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
    },
    addComment() {
      if (!this.add_comment) {
        return;
      }
      var post_data = {
        'post_id': this.postId,
        'body': this.add_comment
      };
      axios.post('/api/comments', post_data).then((res) => {
        this.comments.unshift(res.data);
        this.add_comment = '';
      });
    },
    deleteComment(comment_id) {
      axios.delete('/api/comments/' + comment_id).then((res) => {
        this.comments = this.comments.filter(function (comment) {
          return comment.id != comment_id;
        });
      });
    }
  },
  mounted() {
    this.getPost();
  }
}
</script>
