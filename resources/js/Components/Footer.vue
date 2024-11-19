<template>
    <div style="height: 200px; width: 100%;" class="bg-red">
        <div class="row justify-between items-center q-px-md q-py-lg">
        
        <!-- Left Section: Quick Links -->
        <div class="col-12 col-md-4 q-mb-md">
          <q-list class="text-white">
            <q-item-label header class="text-h6">Quick Links</q-item-label>
            <q-item clickable v-ripple to="/about">About Us</q-item>
            <q-item clickable v-ripple to="/contact">Contact</q-item>
            <q-item clickable v-ripple to="/faq">FAQ</q-item>
            <q-item clickable v-ripple to="/privacy-policy">Privacy Policy</q-item>
            <q-item clickable v-ripple to="/terms">Terms of Service</q-item>
          </q-list>
        </div>

        <!-- Center Section: Social Media -->
        <div class="col-12 col-md-4 text-center q-mb-md">
          <q-item-label header class="text-h6">Follow Us</q-item-label>
          <div class="row justify-center">
            <q-btn flat round icon="mdi-facebook" @click="openLink('https://facebook.com')" />
            <q-btn flat round icon="mdi-twitter" @click="openLink('https://twitter.com')" />
            <q-btn flat round icon="mdi-instagram" @click="openLink('https://instagram.com')" />
            <q-btn flat round icon="mdi-linkedin" @click="openLink('https://linkedin.com')" />
          </div>
        </div>

        <!-- Right Section: Contact Info -->
        <div class="col-12 col-md-4 text-center q-mb-md">
          <q-item-label header class="text-h6">Contact Us</q-item-label>
          <div>
            <q-icon name="mdi-email" /> info@example.com
          </div>
          <div>
            <q-icon name="mdi-phone" /> +123 456 7890
          </div>
          <div>
            <q-icon name="mdi-map-marker" /> 123 Main Street, City, Country
          </div>
        </div>
      </div>
      
      <!-- Footer Bottom: Branding and Copyright -->
      <div class="text-center q-pt-md">
        <q-item-label class="text-subtitle2">&copy; 2024 Your Brand. All rights reserved.</q-item-label>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  
  function openLink(url) {
    window.open(url, '_blank')
  }
  </script>
  
  <style scoped>
  </style>