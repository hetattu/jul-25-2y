<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-2">
        <div class="card" v-for="tag in tags" v-bind:key="tag.id">
          <div class="btn" v-bind:style="{'background-color': tag.color_code}" v-on:click="switchTag(tag.id)">
            {{ tag.name }}
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <span v-for="tagId in post.tags" v-bind:key="'tag' + tagId" v-bind:style="{'background-color': getTagColorCode(tagId)}">{{ getTagName(tagId) }}</span>
        <div class="card">
          <div v-if="!post.subjectEditable" class="card-header">
            <div>{{ post.subject }}</div>
            <button class="btn btn-success" v-on:click="editSubject()">Edit</button>
          </div>
          <div v-else class="card-header">
            <input type="text" class="col-sm-9 form-control" v-model="post.subject">
            <button class="btn btn-success" v-on:click="updatePost()">Update</button>
            <button class="btn btn-danger" v-on:click="cancelEditSubject()">Cancel</button>
          </div>
          <div v-if="!post.bodyEditable" class="card-body">
            <div>{{ post.body }}</div>
            <button class="btn btn-success" v-on:click="editBody()">Edit</button>
          </div>
          <div v-else class="card-body">
            <input type="text" class="col-sm-9 form-control" v-model="post.body">
            <button class="btn btn-success" v-on:click="updatePost()">Update</button>
            <button class="btn btn-primary" v-on:click="cancelEditBody()">Cancel</button>
          </div>
        </div>
        <button class="btn btn-danger" v-on:click="deletePost">Delete</button>
        <form v-on:submit.prevent="addComment">
          <div class="card">
            <div class="card-header">
              <input type="text" class="col-sm-9 form-control" v-model="commentAdd">
              <button type="submit" class="btn btn-success">Add</button>
            </div>
          </div>
        </form>
        <div class="card" v-for="comment in comments" v-bind:key="comment.id">
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
      tags: {},
      commentAdd: ''
    }
  },
  methods: {
    getPost() {
      axios.get('/api/posts/' + this.postId).then((res) => {
        res.data.post.subjectEditable = false;
        res.data.post.bodyEditable = false;

        this.post = res.data.post;

        var addArray = _.cloneDeepWith(res.data.comments, function (val) {
            if (val !== null && typeof val.user_id != 'undefined') {
              val.editable = false;
            }
        });
        this.comments = addArray;
        this.tags = res.data.tags;
      });
    },
    getTagColorCode: function(tagId) {
      var tag = this.tags.find((tag) => tag.id == tagId);
      if (tag) {
        return tag.color_code;
      } else {
        return '';
      }
    },
    getTagName: function(tagId) {
      var tag = this.tags.find((tag) => tag.id == tagId);
      if (tag) {
        return tag.name;
      } else {
        return '';
      }
    },
    updatePost() {
      axios.put('/api/posts/' + this.postId, this.post).then((res) => {
        this.post.subjectEditable = false;
        this.post.bodyEditable = false;
      });
    },
    deletePost() {
      axios.delete('/api/posts/' + this.postId).then((res) => {
        this.$router.push({name: 'post.list'});
      });
    },
    editSubject() {
      this.post.subjectBefore = this.post.subject;
      this.post.subjectEditable = true;
    },
    cancelEditSubject() {
      this.post.subject = this.post.subjectBefore;
      this.post.subjectEditable = false;
    },
    editBody() {
      this.post.bodyBefore = this.post.body;
      this.post.bodyEditable = true;
    },
    cancelEditBody() {
      this.post.body = this.post.bodyBefore;
      this.post.bodyEditable = false;
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
        res.data.data.editable = false;

        this.comments.unshift(res.data.data);
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
    },
    switchTag(tagId) {
      if (this.post.tags.includes(tagId)) {
        console.log('remove');
        this.post.tags = this.post.tags.filter((val) => val != tagId);
        axios.put('/api/posts/' + this.postId, this.post).then((res) => {
          this.post.tagsInfo = this.post.tagsInfo.filter((tag) => tag.id != tagId);
        });
      } else {
        console.log('add');
        this.post.tags.push(tagId);
        axios.put('/api/posts/' + this.postId, this.post).then((res) => {
          var tag = this.tags.find((tag) => tag.id == tagId);
          this.post.tagsInfo.push({
            id: tagId,
            name: tag.name,
            color_code: tag.color_code,
            order: tag.order,
          });
        });
      }
    },
  },
  mounted() {
    this.getPost();
  }
}
</script>
