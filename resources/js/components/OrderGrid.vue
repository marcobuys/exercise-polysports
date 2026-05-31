<script setup>
import { reactive } from 'vue';

const props = defineProps({
    product: { type: Object, required: true },
});

const emit = defineEmits(['add']);

const sizes = [39, 40, 41, 42, 43, 44, 45, 46];
const quantities = reactive({});

function variantFor(size) {
    return (props.product.availability || []).find((a) => a.eu_size === size);
}

function stockFor(size) {
    const variant = variantFor(size);
    return variant ? variant.stock : 0;
}

function addToCart() {
    const lines = [];

    for (const size of sizes) {
        const qty = Number(quantities[size]) || 0;
        const variant = variantFor(size);
        if (qty > 0 && variant) {
            lines.push({
                product_variant_id: variant.variant_id,
                product_id: props.product.id,
                product_name: props.product.name,
                eu_size: size,
                unit_price_cents: props.product.price_cents,
                quantity: qty,
            });
        }
    }

    if (lines.length) {
        emit('add', lines);
        for (const size of sizes) {
            quantities[size] = 0;
        }
    }
}
</script>

<template>
    <div>
        <div class="flex flex-wrap gap-1.5">
            <label v-for="size in sizes" class="flex flex-col items-center">
                <span class="text-[11px] text-slate-500">{{ size }}</span>
                <input
                    v-model="quantities[size]"
                    type="number"
                    min="0"
                    :disabled="stockFor(size) === 0"
                    :placeholder="stockFor(size) === 0 ? '–' : '0'"
                    class="h-9 w-10 rounded border border-slate-300 text-center text-sm disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-300"
                />
            </label>
        </div>
        <button
            class="mt-3 rounded bg-indigo-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-indigo-700"
            @click="addToCart"
        >
            Add to cart
        </button>
    </div>
</template>
