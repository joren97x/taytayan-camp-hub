<script setup>
import DriverLayout from '@/Layouts/DriverLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

// Placeholder for orders data (You should fetch this data from your backend)
const pendingOrders = ref([
  { id: 1, customer: 'John Doe', pickup: 'Milk Tea Shop', destination: '123 Elm Street', scheduledTime: '2024-11-18 12:00 PM', items: '2 Milk Teas, 1 Shake' },
  { id: 2, customer: 'Jane Smith', pickup: 'Shakes & Snacks', destination: '456 Oak Avenue', scheduledTime: '2024-11-18 12:30 PM', items: '1 Bubble Tea, 1 Fries' }
])

const completedOrders = ref([
  { id: 1, customer: 'Alice Brown', deliveryTime: '2024-11-17 2:00 PM' },
  { id: 2, customer: 'Bob Green', deliveryTime: '2024-11-16 5:00 PM' }
])

// Methods to handle order acceptance and completion
const acceptOrder = (orderId) => {
  console.log(`Order ${orderId} accepted`)
  // Add logic to mark the order as accepted (send to the backend)
}

const completeDelivery = (orderId) => {
  console.log(`Order ${orderId} completed`)
  // Add logic to mark the order as completed (send to the backend)
}
</script>

<template>
  <Head title="Driver Dashboard" />

  <div class="dashboard">
    <!-- Pending Orders Section -->
    <section class="pending-orders">
      <h2>Pending Orders</h2>
      <div v-if="pendingOrders.length === 0">
        <p>No pending orders at the moment.</p>
      </div>
      <div v-for="order in pendingOrders" :key="order.id" class="order-card">
        <p><strong>Customer:</strong> {{ order.customer }}</p>
        <p><strong>Pickup Location:</strong> {{ order.pickup }}</p>
        <p><strong>Destination:</strong> {{ order.destination }}</p>
        <p><strong>Scheduled Time:</strong> {{ order.scheduledTime }}</p>
        <p><strong>Items:</strong> {{ order.items }}</p>
        <button @click="acceptOrder(order.id)">Accept Order</button>
      </div>
    </section>

    <!-- Completed Orders Section -->
    <section class="completed-orders">
      <h2>Completed Deliveries</h2>
      <div v-if="completedOrders.length === 0">
        <p>No completed deliveries yet.</p>
      </div>
      <div v-for="order in completedOrders" :key="order.id" class="order-card">
        <p><strong>Customer:</strong> {{ order.customer }}</p>
        <p><strong>Delivery Time:</strong> {{ order.deliveryTime }}</p>
        <button @click="completeDelivery(order.id)">Complete Delivery</button>
      </div>
    </section>
  </div>
</template>

<style scoped>
.dashboard {
  padding: 20px;
}

section {
  margin-bottom: 20px;
}

.order-card {
  border: 1px solid #ccc;
  padding: 15px;
  margin-bottom: 10px;
}

button {
  padding: 10px;
  background-color: #4CAF50;
  color: white;
  border: none;
  cursor: pointer;
}

button:hover {
  background-color: #45a049;
}
</style>
