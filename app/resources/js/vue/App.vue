<template>
  <h1>Find nearest sites</h1>
  <form class="form" @submit.prevent="submit" novalidate>
    <div class="input">
      <label>Lat</label>
      <input v-model="lat" type="text">
    </div>
    <div class="input">
      <label>Lon</label>
      <input v-model="lon" type="text">
    </div>
    <div class="input">
      <button class="button">Show</button>
    </div>
  </form>
  <div v-if="loading" class="loading">Loading...</div>
  <div class="closest-sites">
    <h2>Results:</h2>
    <div v-for="site in sites" :key="site.id" class="site">
      <div class="name">{{ site.site }}</div>
      <div class="agent">
        <span>Agent: </span>
        <span>{{ site.agent_full_name }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const loading = ref(false);
const sites = ref([]);

const lat = ref('');
const lon = ref('');

const submit = async (values) => {
  loading.value = true;

  try {
    const response = await axios.get('/api/closest-sites', {
      params: {
        lat: parseFloat(lat.value),
        lon: parseFloat(lon.value)
      }
    });

    if (response.status === 200) {
      sites.value = response.data;
    }
  } catch (error) {

  }

  loading.value = false;
};
</script>
