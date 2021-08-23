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
      <div class="col-md-2">
        <div class="card" v-for="tag in tags" :key="tag.id">
          <div class="btn" :style="{backgroundColor: tag.color_code}" @click.prevent="switchTag(tag.id)">
            {{ tag.name }}
          </div>
        </div>
      </div>
      <div class="col-md-8">

        <v-card
          class="mx-auto mb-4"
        >
          <v-card-title>
            <div v-if="post.subjectEditable">
              <v-text-field
                v-model="post.subject"
                :counter="50"
                :rules="subjectRules"
                label="Subject"
                required
                @keyup.enter="updatePost()"
                @blur="updatePost()"
              ></v-text-field>
            </div>
            <div v-else @click="editSubject()" class="mb-2">
              {{ post.subject }}
            </div>
          </v-card-title>

          <v-card-text>
            <v-chip
              v-for="tagId in post.tags"
              :key="'tag' + tagId"
              class="mr-2"
              :style="{'background-color': getTagColorCode(tagId)}"
            >
            {{ getTagName(tagId) }}
            </v-chip>
          </v-card-text>

          <v-divider></v-divider>

          <v-card-text>
            <div v-if="post.bodyEditable">
              <v-textarea
                v-model="post.body"
                :rules="bodyRules"
                label="Body"
                rows="3"
                required
                @keyup.enter="updatePost()"
                @blur="updatePost()"
              >
              </v-textarea>
            </div>
            <div v-else @click="editBody()">
              {{ post.body }}
            </div>
          </v-card-text>
        </v-card>

        <v-card
          class="mb-2"
          outlined
        >
          <v-form
            ref="form"
            v-model="valid"
            lazy-validation
            class="pa-2"
          >
            <v-textarea
              v-model="commentAdd"
              :rules="commentRules"
              label="Comment"
              rows="1"
              required
            ></v-textarea>

            <v-btn
              :disabled="!valid"
              color="success"
              class="mt-4"
              @click="addComment()"
            >
              Send
            </v-btn>
          </v-form>
        </v-card>

        <v-card
          v-for="comment in comments"
          :key="comment.id"
          class="mb-2"
          outlined
        >
          <v-card-text>
            <v-icon @click="deleteComment(comment.id)">
              mdi-delete
            </v-icon>
            {{ comment.body }}
          </v-card-text>
        </v-card>
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
      commentAdd: '',

      valid: true,
      subjectRules: [
        v => !!v || 'Subject is required',
      ],
      bodyRules: [
        v => !!v || 'Body is required',
      ],
      commentRules: [
        v => !!v || 'Comment is required',
      ],
    }
  },
  methods: {
    getPost() {
      axios.get('/api/posts/' + this.postId).then((res) => {
        res.data.post.subjectEditable = false;
        res.data.post.bodyEditable = false;

        this.post = res.data.post;

        let addArray = _.cloneDeepWith(res.data.comments, function (val) {
            if (val !== null && typeof val.user_id !== 'undefined') {
              val.editable = false;
            }
        });
        this.comments = addArray;
        this.tags = res.data.tags;
      });
    },
    getTagColorCode: function(tagId) {
      let tag = this.tags.find((tag) => tag.id === tagId);
      if (tag) {
        return tag.color_code;
      } else {
        return '';
      }
    },
    getTagName: function(tagId) {
      let tag = this.tags.find((tag) => tag.id === tagId);
      if (tag) {
        return tag.name;
      } else {
        return '';
      }
    },
    updatePost() {
      if (this.post.subject === this.post.subjectBefore) {
        this.post.subject = this.post.subjectBefore;
        this.post.subjectEditable = false;
        return;
      }
      if (this.post.body === this.post.bodyBefore) {
        this.post.body = this.post.bodyBefore;
        this.post.bodyEditable = false;
        return;
      }

      axios.put('/api/posts/' + this.postId, this.post).then((res) => {
        this.post.subjectEditable = false;
        this.post.bodyEditable = false;
      });
    },
    editSubject() {
      this.post.subjectBefore = this.post.subject;
      this.post.subjectEditable = true;
    },
    editBody() {
      this.post.bodyBefore = this.post.body;
      this.post.bodyEditable = true;
    },
    addComment() {
      if (!this.commentAdd) {
        return;
      }
      let postData = {
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
        this.comments = this.comments.filter((cmt) => cmt.id !== commentId);
      });
    },
    switchTag(tagId) {
      if (this.post.tags.includes(tagId)) {
        this.post.tags = this.post.tags.filter((val) => val !== tagId);
        axios.put('/api/posts/' + this.postId, this.post).then((res) => {
          this.post.tagsInfo = this.post.tagsInfo.filter((tag) => tag.id !== tagId);
        });
      } else {
        this.post.tags.push(tagId);
        axios.put('/api/posts/' + this.postId, this.post).then((res) => {
          let tag = this.tags.find((tag) => tag.id === tagId);
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
