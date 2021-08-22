<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-2">
        <div class="card" v-for="tag in tags" :key="tag.id">
          <div v-if="!selectTags.includes(tag.id)" class="btn btn-outline-primary" :style="{'color': tag.color_code}" @click="switchTag(tag.id)">
            {{ tag.name }}
          </div>
          <div v-else class="btn" :style="{'background-color': tag.color_code}" @click="switchTag(tag.id)">
            {{ tag.name }}
          </div>
        </div>
      </div>

      <div class="col-md-8">
        <form-input v-model="query"/><br>
        <div v-for="post in filteredList" :key="post.id" v-show="post.display">

        <post-card
          :post="post"
          :tags="tags"
        ></post-card>

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
        query: '',
      }
    },

    methods: {
      getPosts() {
        axios.get('/api/posts').then((res) => {
          this.tags = res.data.tags;
          this.selectTags = res.data.tags.map((tag) => tag.id);

          let addArray = _.cloneDeepWith(res.data.posts, function (val) {
            if (val !== null && typeof val.user_id !== 'undefined') {
              val.display = true;
            }
          });
          this.posts = addArray;
        });
      },
      switchTag(tagId) {
        if (this.selectTags.includes(tagId)) {
          this.selectTags = this.selectTags.filter((val) => val !== tagId);
        } else {
          this.selectTags.push(tagId);
        }

        this.posts.forEach((post) => {
          if (post.tags.length === 0) {
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
    },

    computed: {
      filteredList() {
        let that = this;
        return this.posts.filter(post => {
          return post.subject.indexOf(that.query) !== -1;
        });
      }
    }
  }
</script>
