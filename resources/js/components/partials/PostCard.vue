<template>
  <v-card
    class="mx-auto mb-4"
  >
    <v-card-title>
      {{ post.subject }}
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

    <v-card-actions>
      <v-btn
        color="primary lighten-2"
        text
        :to="{name: 'post.show', params: {postId: post.id}}"
      >
        Show
      </v-btn>

      <v-spacer></v-spacer>

      <v-btn
        icon
        @click="show = !show"
      >
        <v-icon>{{ show ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon>
      </v-btn>
    </v-card-actions>

    <v-expand-transition>
      <div v-show="show">
        <v-divider></v-divider>

        <v-card-text>
          {{ post.body }}
        </v-card-text>
      </div>
    </v-expand-transition>
  </v-card>
</template>

<script>
  export default {
    props: {
      post: Object,
      tags: Array,
    },

    data: () => ({
      show: false,
    }),

    methods: {
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
    }
  }
</script>