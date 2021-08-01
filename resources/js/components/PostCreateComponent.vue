<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-sm-6">
        <form v-on:submit.prevent="submit">
          <div class="form-group row">
            <label for="title" class="col-sm-3 col-form-label">Subject</label>
            <input type="text" class="col-sm-9 form-control" v-model="subject">
          </div>
          <div class="form-group row">
            <label for="content" class="col-sm-3 col-form-label">Body</label>
            <input type="text" class="col-sm-9 form-control" v-model="body">
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      subject: '',
      body: ''
    }
  },
  methods: {
    submit() {
      var post_data = {
        'subject': this.subject,
        'body': this.body
      };
      axios.post('/api/posts', post_data)
        .then((res) => {
          this.$router.push({name: 'post.list'});
        });
    }
  }
}
</script>
