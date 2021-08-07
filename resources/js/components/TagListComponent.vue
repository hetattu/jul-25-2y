<template>
  <div class="container">
    <div class="row justify-content-center" v-for="tag in tags" v-bind:key="tag.id">
      <div class="col-md-8">
        <div class="card">
          <div class="card-body" v-bind:style="{color: '#' + tag.color_code}">
            {{ tag.name }}
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
      tags: []
    }
  },
  methods: {
    getTags() {
      axios.get('/api/tags').then((res) => {
        this.tags = res.data;
      });
    }
  },
  mounted() {
    this.getTags();
  }
}
</script>
