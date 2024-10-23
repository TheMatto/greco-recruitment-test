<template>
  <h1>Find nearest sites</h1>
  <form class="form" @submit.prevent="submit" novalidate>
    <div v-show="manualEntry" class="input">
      <label>Lat</label>
      <input v-model="lat" type="text">
    </div>
    <div v-show="manualEntry" class="input">
      <label>Lon</label>
      <input v-model="lon" type="text">
    </div>
    <div v-show="!manualEntry" class="input">
      <label>Location</label>
      <vue-google-autocomplete
        id="autocomplete-location"
        placeholder="Start typing"
        @placechanged="onPlaceChanged"
      />
    </div>
    <div class="input">
      <label>
        <input v-model="manualEntry" type="checkbox">
        <span>Manual coordinates entry</span>
      </label>
    </div>
    <div class="input">
      <button class="button">Show</button>
    </div>
  </form>

  <div v-if="loading" class="loading">Loading...</div>
  <div v-show="sites.length" class="closest-sites">
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
import VueGoogleAutocomplete from 'vue-google-autocomplete';

const loading = ref(false);
const sites = ref([]);

const manualEntry = ref(false);

const lat = ref('');
const lon = ref('');

const onPlaceChanged = (addressData, placeResultData, id) => {
  if (addressData.latitude && addressData.longitude) {
    lat.value = addressData.latitude;
    lon.value = addressData.longitude;
  }
}

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
