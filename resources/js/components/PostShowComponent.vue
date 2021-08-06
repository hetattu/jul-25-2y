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
              <input type="text" class="col-sm-9 form-control" v-model="commentAdd">
              <button type="submit" class="btn btn-success">Add</button>
            </div>
          </div>
        </form>
      </div>
      <div class="col-md-8" v-for="comment in comments" v-bind:key="comment.id">
        <div class="card">
          <div v-if="!comment.editable" class="card-body">
            <div>{{ comment.body }}</div>
            <button class="btn btn-success" v-on:click="editComment(comment.id)">Edit</button>
            <button class="btn btn-danger" v-on:click="deleteComment(comment.id)">Delete</button>
          </div>
          <div v-else class="card-body">
            <input type="text" class="col-sm-9 form-control" v-model="comment.body">
            <button class="btn btn-primary" v-on:click="updateComment(comment.id)">Update</button>
            <button class="btn btn-danger" v-on:click="cancelEditComment(comment.id)">Cancel</button>
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
      commentAdd: ''
    }
  },
  methods: {
    getPost() {
      axios.get('/api/posts/' + this.postId).then((res) => {
        this.post = res.data.post;

        var addArray = _.cloneDeepWith(res.data.comments, function (val) {
            if (val !== null && typeof val.post_id != 'undefined') {
              val.editable = false;
            }
        });
        this.comments = addArray;
      });
    },
    deletePost() {
      axios.delete('/api/posts/' + this.postId).then((res) => {
        this.$router.push({name: 'post.list'});
      });
    },
    submit() {
      var postData = {
        'subject': this.post.subject,
        'body': this.post.body
      };
      axios.put('/api/posts/' + this.postId, this.post).then((res) => {
        this.post = res.data;
      });
    },
    addComment() {
      if (!this.commentAdd) {
        return;
      }
      var postData = {
        'post_id': this.postId,
        'body': this.commentAdd
      };
      axios.post('/api/comments', postData).then((res) => {
        this.comments.unshift(res.data);
        this.commentAdd = '';
      });
    },
    deleteComment(commentId) {
      axios.delete('/api/comments/' + commentId).then((res) => {
        this.comments = this.comments.filter((cmt) => cmt.id != commentId);
      });
    },
    editComment(commentId) {
      var comment = this.comments.find((cmt) => cmt.id == commentId);
      comment.bodyBefore = comment.body;
      comment.editable = true;
    },
    updateComment(commentId) {
      var comment = this.comments.find((cmt) => cmt.id == commentId);
      axios.put('/api/comments/' + commentId, comment).then((res) => {
        comment.editable = false;
      });
    },
    cancelEditComment(commentId) {
      var comment = this.comments.find((cmt) => cmt.id == commentId);
      comment.body = comment.bodyBefore;
      comment.editable = false;
    }
  },
  mounted() {
    this.getPost();
  }
}
</script>
