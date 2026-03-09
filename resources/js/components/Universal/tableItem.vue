<template>
    <div>
        <v-row dense>
            <v-col cols="3">Text</v-col>
            <v-col cols="2">Amount</v-col>
            <v-col cols="2">Price</v-col>
            <v-col cols="1">VAT</v-col>
            <v-col cols="2">Currency</v-col>
            <v-col cols="1">Total</v-col>
            <v-col cols="1">Action</v-col>
        </v-row>
        <v-row dense v-for="(item, index) in items" :key="item.id">
            <v-col cols="3">
                <v-text-field
                    density="compact"
                    v-model="item.text"
                    label="Text"
                    maxlength="100"
                    counter
                    :rules="[v => (v || '').length <= 100 || 'Maximum of 100 characters']"
                />
            </v-col>
            <v-col cols="2">
                <v-number-input
                    density="compact"
                    hide-details
                    controlVariant="stacked"
                    v-model="item.amount"
                    label="Amount"
                    :min="1"
                    @update:model-value="computeTotal(item)"
                    v-on:change="computeTotal(item);"
                    v-on:keyup="computeTotal(item)"
                />
            </v-col>
            <v-col cols="2">
                <v-number-input
                    density="compact"
                    hide-details
                    :precision="2"
                    decimalSeparator=","
                    controlVariant="hidden"
                    v-model="item.price"
                    label="Price without VAT"
                    v-on:change="computeTotal(item)"
                    v-on:keyup="computeTotal(item)"
                />
            </v-col>
            <v-col cols="1">
                <v-select
                    dense
                    hide-details
                    density="compact"
                    v-model="item.vat"
                    :items="taxes"
                    item-text="title"
                    item-value="key"
                    label="VAT"
                />
            </v-col>
            <v-col cols="2">
                <v-select
                    dense
                    hide-details
                    density="compact"
                    v-model="item.currency"
                    :items="currencies"
                    label="Currency"
                />
            </v-col>
            <v-col cols="1" align="right" class="pr-2 pt-3">
                {{ item.total }}
            </v-col>
            <v-col cols="1">
                <v-tooltip text="Remove item">
                    <template v-slot:activator="{ props }">
                        <v-btn
                            class="pl-1 pt-1"
                            icon="mdi-delete-outline"
                            density="compact"
                            @click="removeItem(index)"
                            v-bind="props"
                        />
                    </template>
                </v-tooltip>
            </v-col>
        </v-row>
        <v-row dense>
            <v-col cols="2">
                <v-btn
                    prepend-icon="mdi-plus"
                    @click="addItem"
                >
                    Add next item
                </v-btn>
            </v-col>
        </v-row>
    </div>
</template>

<script>
export default {
    name: 'tableItem',
    data: () => {
        return {
        }
    },
    props: [
        'items',
        'defaultCurrency',
        'currencies',
        'taxes',
        'defaultVat',
        'computeTotals',
        'update'
    ],
    mounted() {
        if (!this.update) {
            this.items.push({
                text: '',
                price: null,
                amount: 1,
                vat: this.defaultVat,
                total: 0,
                currency: this.defaultCurrency,
            })
        } else {
            this.items.forEach((item) => {
                this.computeTotal(item)
            })
        }
    },
    methods: {
        addItem() {
            this.items.push({
                text: '',
                price: null,
                amount: 1,
                vat: 0,
                total: 0,
                currency: this.defaultCurrency
            })
        },
        removeItem(index) {
            this.items.splice(index, 1)
        },
        computeTotal(item) {
            item.total = this.priceFormat(item.price * item.amount * (1 + item.vat / 100))
            this.computeTotals()
        },
        priceFormat: function (value) {
            return new Intl.NumberFormat().format(value)
        }
    },
}
</script>

