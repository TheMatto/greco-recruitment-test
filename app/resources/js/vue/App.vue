<template>
  <h1>Find nearest sites</h1>
  <form class="form" @submit.prevent="submit(values)" novalidate>
    <div v-show="manualEntry" class="input" :class="{ error: errors.lat }">
      <label>Lat</label>
      <input
        v-model="values.lat"
        type="text"
        @focus="errors.lat = false"
      >
    </div>
    <div v-show="manualEntry" class="input" :class="{ error: errors.lon }">
      <label>Lon</label>
      <input
        v-model="values.lon"
        type="text"
        @focus="errors.lon = false"
      >
    </div>
    <div v-show="!manualEntry" class="input" :class="{ error: errors.lat || errors.lon }">
      <label>Location</label>
      <vue-google-autocomplete
        id="autocomplete-location"
        placeholder="Start typing"
        @placechanged="onPlaceChanged"
        @focus="errors.lat = errors.lon = false"
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
import { ref, reactive } from 'vue';
import axios from 'axios';
import VueGoogleAutocomplete from 'vue-google-autocomplete';

const loading = ref(false);
const sites = ref([]);

const manualEntry = ref(false);

const values = reactive({
  lat: '',
  lon: ''
});

const errors = reactive({
  lat: false,
  lon: false
});

const onPlaceChanged = (addressData, placeResultData, id) => {
  if (addressData.latitude && addressData.longitude) {
    values.lat = addressData.latitude;
    values.lon = addressData.longitude;
  }
}

const submit = async (values) => {

  const floatRegex = /^-?\d+(\.\d+)?$/;

  let error = false;

  for (const [key, value] of Object.entries(values)) {
    if (!floatRegex.test(value)) {
      errors[key] = true;

      error = true;
    } else {
      errors[key] = false;
    }
  }

  if (error) return;

  loading.value = true;

  try {
    const response = await axios.get('/api/closest-sites', {
      params: {
        lat: parseFloat(values.lat),
        lon: parseFloat(values.lon)
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
