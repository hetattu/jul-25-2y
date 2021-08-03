<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            {{ post.subject }}
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
        console.log(res);
      });
    }
  },
  mounted() {
    this.getPost();
  }
}
</script>
