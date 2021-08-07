<template>
<div>
  <router-link v-bind:to="{name: 'post.list'}"><button class="btn btn-link">Post</button></router-link><br>
  <router-link v-bind:to="{name: 'tag.list'}"><button class="btn btn-link">Tag</button></router-link>
</div>
</template>