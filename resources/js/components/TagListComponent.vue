<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <input type="text" class="col-sm-9 form-control" v-model="tag.name">
            <button class="btn" v-on:click="switchColor()" v-bind:style="{'background-color': colors.hex}" style="color:white; line-height:2em;">{{ colors.hex }}</button>
            <compact-picker v-if="tag.colorEditable" v-model="colors" />
            <button v-if="!tag.name" class="btn btn-primary">Add</button>
            <button v-else class="btn btn-success" v-on:click="addTag()">Add</button>
          </div>
        </div>
        <div class="card" v-for="tag in tags" v-bind:key="tag.id">
          <div class="card-body" v-bind:style="{'background-color': tag.color_code}" style="color:white;">
            {{ tag.name }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Compact } from 'vue-color';

export default {
  data: function () {
    return {
      colors: { hex: "#" + Math.floor(Math.random() * 16777215).toString(16) },
      tag: {
        name: '',
        colorEditable: false
      },
      tags: []
    }
  },
  components: {
    'compact-picker': Compact
  },
  methods: {
    getTags() {
      axios.get('/api/tags').then((res) => {
        this.tags = res.data;
      });
    },
    switchColor() {
      if (this.tag.colorEditable) {
        this.tag.color_code = this.colors.hex;
      }
      this.tag.colorEditable = !this.tag.colorEditable;
    },
    addTag() {
      var post_data = {
        'name': this.tag.name,
        'color_code': this.colors.hex,
      };
      axios.post('/api/tags', post_data).then((res) => {
        this.tags.unshift(res.data);
        this.tag.name = '';
        this.tag.colorEditable = false;
        this.colors = { hex: "#" + Math.floor(Math.random() * 16777215).toString(16) };
      });
    }
  },
  mounted() {
    this.getTags();
  }
}
</script>
