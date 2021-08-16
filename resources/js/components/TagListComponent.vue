<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">

            <form-input
              v-model="tag.name"
              v-bind:input-type="'text'"
              v-bind:maxlength="100"
              v-bind:iclass="'col-sm-9 form-control'"
              v-bind:title="'name'"
            />

            <button class="btn" v-on:click="switchColor(null)" v-bind:style="{backgroundColor: colors.hex}" style="color:white; line-height:2em;">{{ colors.hex }}</button>
            <compact-picker v-if="tag.colorEditable" v-model="colors" />
            <button v-if="!tag.name" class="btn btn-primary">Add</button>
            <button v-else class="btn btn-success" v-on:click.prevent="addTag()">Add</button>
          </div>
        </div>
        <div class="card" v-for="tag in tags" v-bind:key="tag.id">
          <div v-if="!tag.editable" class="card-body" v-bind:style="{backgroundColor: tag.colors.hex}" style="color:white;">
            <span>{{ tag.name }}</span>
            <button class="btn btn-outline-primary" style="color:white;" v-on:click="editTag(tag.id)">Edit</button>
            <button class="btn btn-outline-danger" style="color:white;" v-on:click.prevent="deleteTag(tag.id)">Delete</button>
          </div>
          <div v-else class="card-body" style="color:white;">

            <form-input
              v-model="tag.name"
              v-bind:input-type="'text'"
              v-bind:maxlength="100"
              v-bind:iclass="'col-sm-9 form-control'"
              v-bind:title="'name'"
            />

            <button class="btn" v-on:click="switchColor(tag.id)" v-bind:style="{backgroundColor: tag.colors.hex}" style="color:white; line-height:2em;">{{ tag.colors.hex }}</button>
            <compact-picker v-if="tag.colorEditable" v-model="tag.colors" />
            <button class="btn btn-success" style="color:white;" v-on:click.prevent="updateTag(tag.id)">Update</button>
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
        let addArray = _.cloneDeepWith(res.data.data, function (val) {
          if (val !== null && typeof val.id !== 'undefined') {
            val.colors = { hex: val.color_code };
            val.colorEditable = false;
            val.editable = false;
          }
        });
        this.tags = addArray;
      });
    },
    switchColor(tagId) {
      let tag = tagId ? this.tags.find((tag) => tag.id === tagId) : this.tag;
      if (tag.colorEditable) {
        tag.color_code = this.colors.hex;
      }
      tag.colorEditable = !tag.colorEditable;
    },
    addTag() {
      let postData = {
        'name': this.tag.name,
        'color_code': this.colors.hex,
      };
      axios.post('/api/tags', postData).then((res) => {
        res.data.colors = { hex: res.data.color_code };
        this.tags.push(res.data);
        this.tag.name = '';
        this.tag.colorEditable = false;
        this.colors = { hex: "#" + Math.floor(Math.random() * 16777215).toString(16) };
      });
    },
    editTag(tagId) {
      let tag = this.tags.find((tag) => tag.id === tagId);
      tag.beforeName = tag.name;
      tag.beforeColors = tag.colors;
      tag.editable = true;
    },
    cancelEditTag(tagId) {
      let tag = this.tags.find((tag) => tag.id === tagId);
      tag.name = tag.beforeName;
      tag.colors = tag.beforeColors;
      tag.editable = false;
    },
    updateTag(tagId) {
      let tag = this.tags.find((tag) => tag.id === tagId);
      let postData = {
        'name': tag.name,
        'color_code': tag.colors.hex,
      };
      axios.put('/api/tags/' + tagId, postData).then((res) => {
        tag.editable = false;
      });
    },
    deleteTag(tagId) {
      axios.delete('/api/tags/' + tagId).then((res) => {
        this.tags = this.tags.filter((tag) => tag.id !== tagId);
      });
    }
  },
  mounted() {
    this.getTags();
  }
}
</script>
