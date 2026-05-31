<script setup>
import { ref } from 'vue';
import MiniCart from './MiniCart.vue';
import CustomerSelect from './CustomerSelect.vue';
import ProductList from './ProductList.vue';
import ProductDetail from './ProductDetail.vue';
import Checkout from './Checkout.vue';

const customer = ref(null);
const view = ref('select-customer');
const selectedProduct = ref(null);
const cart = ref([]);

function selectCustomer(c) {
    customer.value = c;
    view.value = 'plp';
}

function changeCustomer() {
    customer.value = null;
    cart.value = [];
    selectedProduct.value = null;
    view.value = 'select-customer';
}

function addToCart(lines) {
    for (const line of lines) {
        const existing = cart.value.find((l) => l.product_variant_id === line.product_variant_id);
        if (existing) {
            existing.quantity += line.quantity;
        } else {
            cart.value.push({ ...line });
        }
    }
}

function removeLine(variantId) {
    cart.value = cart.value.filter((l) => l.product_variant_id !== variantId);
}

function openProduct(product) {
    selectedProduct.value = product;
    view.value = 'pdp';
}

function orderPlaced() {
    cart.value = [];
}
</script>

<template>
    <div class="min-h-screen">
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto flex max-w-5xl items-center justify-between px-4 py-3">
                <div class="flex items-baseline gap-2">
                    <span class="text-xl font-bold tracking-tight text-slate-900">Poly<span class="text-indigo-600">Sports</span></span>
                    <span class="text-xs uppercase tracking-wide text-slate-400">Wholesale back-office</span>
                </div>
                <button
                    v-if="customer"
                    class="text-sm text-slate-500 hover:text-slate-800"
                    @click="changeCustomer"
                >
                    {{ customer.company_name }} ({{ customer.tier }}) · change
                </button>
            </div>
        </header>

        <MiniCart
            v-if="customer"
            :lines="cart"
            @remove="removeLine"
            @checkout="view = 'checkout'"
        />

        <main class="mx-auto max-w-5xl px-4 py-6">
            <CustomerSelect
                v-if="view === 'select-customer'"
                @select="selectCustomer"
            />

            <ProductList
                v-else-if="view === 'plp'"
                :customer="customer"
                @add-to-cart="addToCart"
                @open="openProduct"
            />

            <ProductDetail
                v-else-if="view === 'pdp'"
                :customer="customer"
                :product="selectedProduct"
                @add-to-cart="addToCart"
                @back="view = 'plp'"
            />

            <Checkout
                v-else-if="view === 'checkout'"
                :customer="customer"
                :lines="cart"
                @placed="orderPlaced"
                @back="view = 'plp'"
            />
        </main>
    </div>
</template>
