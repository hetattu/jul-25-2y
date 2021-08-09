<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <input type="text" class="col-sm-9 form-control" v-model="tag.name">
            <button class="btn" v-on:click="switchColor(null)" v-bind:style="{'background-color': colors.hex}" style="color:white; line-height:2em;">{{ colors.hex }}</button>
            <compact-picker v-if="tag.colorEditable" v-model="colors" />
            <button v-if="!tag.name" class="btn btn-primary">Add</button>
            <button v-else class="btn btn-success" v-on:click="addTag()">Add</button>
          </div>
        </div>
        <div class="card" v-for="tag in tags" v-bind:key="tag.id">
          <div v-if="!tag.editable" class="card-body" v-bind:style="{'background-color': tag.colors.hex}" style="color:white;">
            <span>{{ tag.name }}</span>
            <button class="btn btn-outline-primary" style="color:white;" v-on:click="editTag(tag.id)">Edit</button>
            <button class="btn btn-outline-danger" style="color:white;" v-on:click="deleteTag(tag.id)">Delete</button>
          </div>
          <div v-else class="card-body" style="color:white;">
            <input type="text" class="col-sm-9 form-control" v-model="tag.name">
            <button class="btn" v-on:click="switchColor(tag.id)" v-bind:style="{'background-color': tag.colors.hex}" style="color:white; line-height:2em;">{{ tag.colors.hex }}</button>
            <compact-picker v-if="tag.colorEditable" v-model="tag.colors" />
            <button class="btn btn-success" style="color:white;" v-on:click="updateTag(tag.id)">Update</button>
            <button class="btn btn-danger" style="color:white;" v-on:click="cancelEditTag(tag.id)">Cancel</button>
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
        var addArray = _.cloneDeepWith(res.data, function (val) {
          if (val !== null && typeof val.id != 'undefined') {
            val.colors = { hex: val.color_code };
            val.colorEditable = false;
            val.editable = false;
          }
        });
        this.tags = addArray;
      });
    },
    switchColor(tagId) {
      var tag = tagId ? this.tags.find((tag) => tag.id == tagId) : this.tag;
      if (tag.colorEditable) {
        tag.color_code = this.colors.hex;
      }
      tag.colorEditable = !tag.colorEditable;
    },
    addTag() {
      var postData = {
        'name': this.tag.name,
        'color_code': this.colors.hex,
      };
      axios.post('/api/tags', postData).then((res) => {
        this.tags.unshift(res.data);
        this.tag.name = '';
        this.tag.colorEditable = false;
        this.colors = { hex: "#" + Math.floor(Math.random() * 16777215).toString(16) };
      });
    },
    editTag(tagId) {
      var tag = this.tags.find((tag) => tag.id == tagId);
      tag.beforeName = tag.name;
      tag.beforeColors = tag.colors;
      tag.editable = true;
    },
    cancelEditTag(tagId) {
      var tag = this.tags.find((tag) => tag.id == tagId);
      tag.name = tag.beforeName;
      tag.colors = tag.beforeColors;
      tag.editable = false;
    },
    updateTag(tagId) {
      var tag = this.tags.find((tag) => tag.id == tagId);
      var postData = {
        'name': tag.name,
        'color_code': tag.colors.hex,
      };
      axios.put('/api/tags/' + tagId, postData).then((res) => {
        tag.editable = false;
      });
    },
    deleteTag(tagId) {
      axios.delete('/api/tags/' + tagId).then((res) => {
        this.tags = this.tags.filter((tag) => tag.id != tagId);
      });
    }
  },
  mounted() {
    this.getTags();
  }
}
</script>
