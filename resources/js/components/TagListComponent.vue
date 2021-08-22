<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">

        <v-card v-show="tags.length < 10" class="mx-auto mb-4">
          <div
            :style="{'background-color': colors.hex}"
            @click="switchColor(0)"
          >
          <br>
          <br>
          </div>
          <compact-picker v-if="colorEditableTab === 0" v-model="colors" />
          <v-form
            ref="form"
            v-model="valid"
            lazy-validation
            class="pa-2"
          >
            <v-text-field
              v-model="tag.name"
              :counter="50"
              :rules="nameRules"
              label="Name"
              required
            ></v-text-field>

            <v-btn
              :disabled="!valid"
              color="success"
              class="mt-4"
              @click="create"
            >
              Create
            </v-btn>
          </v-form>
        </v-card>

        <v-card
          class="mx-auto mb-4"
          v-for="tag in tags"
          :key="tag.id"
        >
          <div
            :style="{'background-color': tag.colors.hex}"
            @click="switchColor(tag.id)"
          >
          <br>
          </div>
          <compact-picker v-if="colorEditableTab === tag.id" v-model="tag.colors" />
          <v-card-title>
            <div v-if="!tag.editable" @click="editTag(tag.id)" class="mb-2">
              {{ tag.name }}
            </div>
            <div v-else>
              <v-text-field
                v-model="tag.name"
                :counter="50"
                :rules="nameRules"
                label="Name"
                required
                @keyup.enter="updateTag(tag.id)"
                @blur="updateTag(tag.id)"
              ></v-text-field>
            </div>
          </v-card-title>
        </v-card>
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
      },
      tags: [],

      valid: true,
      nameRules: [
        v => !!v || 'Name is required',
      ],
      colorEditableTab: null,
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
            val.editable = false;
          }
        });
        this.tags = addArray;
      });
    },
    switchColor(tagId) {
      let tag = tagId > 0 ? this.tags.find((tag) => tag.id === tagId) : this.tag;
      if (tagId > 0) {
        this.updateTag(this.colorEditableTab);
      }

      if (this.colorEditableTab === tagId) {
        this.colorEditableTab = null;

      } else {
        if (tagId > 0) {
          tag.beforeColors = tag.colors;
        }
        this.colorEditableTab = tagId;
      }
    },
    addTag() {
      let postData = {
        'name': this.tag.name,
        'color_code': this.colors.hex,
      };

      axios.post('/api/tags', postData).then((res) => {
        res.data.data.colors = { hex: res.data.data.color_code };
        this.tags.push(res.data.data);
        this.tag.name = '';
        this.colors = { hex: "#" + Math.floor(Math.random() * 16777215).toString(16) };
      });
    },
    editTag(tagId) {
      let tag = this.tags.find((tag) => tag.id === tagId);
      tag.beforeName = tag.name;
      tag.editable = true;
    },
    updateTag(tagId) {
      let tag = this.tags.find((tag) => tag.id === tagId);
      if (!tag) {
        return;
      } else if (tag.name === tag.beforeName || tag.name === '') {
        tag.editable = false;
        return;
      } else if (tag.colors.hex === tag.beforeColors.hex) {
        return;
      }

      let postData = {
        'name': tag.name,
        'color_code': tag.colors.hex,
      };
      axios.put('/api/tags/' + tagId, postData).then((res) => {
        tag.editable = false;
      });
    },

    create() {
      if (this.$refs.form.validate()) {
        this.addTag();
      }
    }
  },
  mounted() {
    this.getTags();
  }
}
</script>
