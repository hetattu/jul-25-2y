<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-2">
        <div class="card" v-for="tag in tags" v-bind:key="tag.id">
          <div v-if="!selectTags.includes(tag.id)" class="btn btn-outline-primary" v-bind:style="{'color': tag.color_code}" v-on:click="switchTag(tag.id)">
            {{ tag.name }}
          </div>
          <div v-else class="btn" v-bind:style="{'background-color': tag.color_code}" v-on:click="switchTag(tag.id)">
            {{ tag.name }}
          </div>
        </div>
      </div>

      <div class="col-md-8">
        <div v-for="post in posts" v-bind:key="post.id" v-show="post.display">
          <span v-for="tagId in post.tags" v-bind:key="'tag' + tagId" v-bind:style="{'background-color': getTagColorCode(tagId)}">{{ getTagName(tagId) }}</span>
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
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        posts: [],
        tags: [],
        selectTags: [],
      }
    },
    methods: {
      getPosts() {
        axios.get('/api/posts').then((res) => {
          this.tags = res.data.tags;
          this.selectTags = res.data.tags.map((tag) => tag.id);

          var addArray = _.cloneDeepWith(res.data.posts, function (val) {
            if (val !== null && typeof val.user_id !== 'undefined') {
              val.display = true;
            }
          });
          this.posts = addArray;
        });
      },
      getTagColorCode: function(tagId) {
        var tag = this.tags.find((tag) => tag.id == tagId);
        return tag.color_code;
      },
      getTagName: function(tagId) {
        var tag = this.tags.find((tag) => tag.id == tagId);
        return tag.name;
      },
      switchTag(tagId) {
        if (this.selectTags.includes(tagId)) {
          this.selectTags = this.selectTags.filter((val) => val != tagId);
        } else {
          this.selectTags.push(tagId);
        }

        this.posts.forEach((post) => {
          if (post.tags.length == 0) {
            post.display = true;
          } else if ([...this.selectTags, post.tags].filter((item) => this.selectTags.includes(item) && post.tags.includes(item)).length > 0) {
            post.display = true;
          } else {
            post.display = false;
          }
        });
      }
    },
    mounted() {
      this.getPosts();
    }
  }
</script>
