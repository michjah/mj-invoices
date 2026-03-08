<template>
    <div style="width:100%">
        <v-breadcrumbs :items="breadcrumbs"></v-breadcrumbs>
        <h2 class="ml-4">New Invoice</h2>

        <v-spacer class="mt-2"/>

        <v-form
            :action="formUpdateUrl"
            method="PUT"
            @submit.prevent="onFormSubmit"
            ref="form"
        >
            <v-container class="ma-0">
                <v-row>
                    <v-col cols="6" rounded="lg">
                        <v-card class="mb-2">
                            <v-card-item>
                                <v-row dense>
                                    <v-col cols="12" dense>
                                        <v-text-field
                                            v-model="invoice.invoice_id"
                                            label="Invoice No."
                                            :rules="rules.invoice_id"
                                        />
                                    </v-col>
                                </v-row>
                                <v-row dense>
                                    <v-col cols="12" dense>
                                        <v-text-field
                                            v-model="invoice.order_id"
                                            label="Order No."
                                        />
                                    </v-col>
                                </v-row>
                                <v-row dense>
                                    <v-col cols="12" dense>
                                        <v-date-input
                                            v-model="invoice.invoice_date"
                                            label="Invoice Date"
                                            input-format="yyyy-MM-dd"
                                            :rules="rules.invoice_date"
                                        />
                                        <v-date-input
                                            v-model="invoice.due_date"
                                            label="Due Date"
                                            input-format="yyyy-MM-dd"
                                            :rules="rules.due_date"
                                        />
                                        <v-date-input
                                            v-model="invoice.duzp_date"
                                            label="DUZP Date"
                                            input-format="yyyy-MM-dd"
                                            :rules="rules.duzp_date"
                                        />
                                    </v-col>
                                </v-row>
                            </v-card-item>
                        </v-card>
                    </v-col>
                    <v-col cols="6">
                        <v-card class="mb-2">
                            <v-card-item>
                                <v-row dense>
                                    <v-col cols="12" dense>
                                        <v-select
                                            v-model="invoice.customer_id"
                                            :items="contacts"
                                            :rules="rules.customer"
                                            item-title="name"
                                            item-value="id"
                                            label="Search customer (you)"
                                            hide-no-data
                                            clearable
                                        />
                                    </v-col>
                                </v-row>

                                <v-row dense>
                                    <v-col cols="12" dense>
                                        <v-select
                                            v-model="invoice.supplier_id"
                                            :items="contacts"
                                            :rules="rules.supplier"
                                            item-title="name"
                                            item-value="id"
                                            label="Search contact"
                                            hide-no-data
                                            clearable
                                        />
                                    </v-col>
                                </v-row>

                                <v-row dense>
                                    <v-col cols="12" dense>
                                        <v-textarea
                                            label="Note"
                                            v-model="invoice.note"
                                        />
                                        {{ invoice.total_price }} - {{ invoice.total_price_vat }} - {{ invoice.total_price_without_vat}}
                                    </v-col>
                                </v-row>
                            </v-card-item>
                        </v-card>
                    </v-col>
                </v-row>

                <v-row dense>
                    <v-col cols="12">
                        <div class="pl-2">Items</div>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" class="ma-0 pa-0 ml-2">
                        <table-item
                            :items="invoice.items"
                            :default-currency="defaultCurrency"
                            :default-vat="defaultVat"
                            :currencies="currencies"
                            :taxes="taxes"
                            :compute-totals="computeTotals"
                        />
                    </v-col>
                </v-row>

                <v-row class="ml-2 mt-4">
                    <v-btn type="submit" color="primary">Save invoice</v-btn>
                </v-row>
            </v-container>
        </v-form>
    </div>
</template>

<script>
import { nextTick } from 'vue'
import { VDateInput } from 'vuetify/labs/VDateInput'
import axios from "axios";
import TableItem from "../Universal/tableItem.vue";

export default {
    name: 'newInvoice',
    components: {TableItem, VDateInput},
    data: () => {
        return {
            breadcrumbs: [
                {title: 'Home', disabled: false, href: '/'},
                {title: 'Invoices', disabled: false, href: '/invoices'},
                {title: 'New Invoice', disabled: true},
            ],
            formUpdateUrl: '/api/invoices',
            currentDate: new Date(),
            includedVat: true,
            invoice: {
                invoice_id: null,
                supplier_id: null,
                customer_id: null,
                invoice_date: new Date().toISOString().split('T')[0],
                due_date: new Date(new Date().setMonth(new Date().getMonth() + 1)).toISOString().split('T')[0],
                duzp_date: new Date(new Date().setMonth(new Date().getMonth() + 1)).toISOString().split('T')[0],
                status: 'DRAFT',
                note: '',
                total: null,
                total_price_vat: null,
                total_price_without_vat: null,
                items: [],
            },
            defaultCurrency: 'CZK',
            defaultVat: 21,
            currencies: [],
            taxes: [],
            contacts: [],
            rules: {
                invoice_id: [
                    (v) => !!v || 'Invoice ID is required',
                    (v) => /^[0-9]+$/.test(v) || 'Invoice ID must be numeric',
                ],
                invoice_date: [
                    (v) => !!v || 'Invoice date is required',
                ],
                due_date: [
                    (v) => !!v || 'Due date is required',
                ],
                supplier: [
                    (v) => !!v || 'Supplier is required',
                ],
                customer: [
                    (v) => !!v || 'Customer is required',
                ],
                items: [
                    (v) => !!v || 'Items are required',
                ],
                price: [
                    (v) => !!v || 'Price is required',
                ]
            },
        }
    },
    mounted() {
        var self = this
        axios.get('/api/collect').then((response) => {
            self.invoice.invoice_id = response.data.nextInvoiceNumber
            self.defaultCurrency = response.data.defaultCurrency
            self.currencies = response.data.currencies
            self.taxes = response.data.taxes
        })

        this.searchContact()
    },
    methods: {
        onFormSubmit(e) {
            e.preventDefault()

            const self = this
            const form = this.$refs.form

            form.validate().then(async (result) => {
                this.valid = result.valid

                if (!result.valid) {
                    await nextTick()

                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    })
                } else {
                    this.computeTotals()
                    axios.post(this.formUpdateUrl, this.invoice).then((response) => {
                        console.log(response.data)
                    })
                }
            })
        },
        searchContact() {
            let self = this
            axios.get('/api/contacts')
                .then((response) => {
                    response.data.data.forEach(contact => {
                        let name = contact.name + ' (' + contact.ic + ')'
                        self.contacts.push({id: contact.id, name: name})
                    })
                })
        },
        computeTotals() {
            this.useVat(this.invoice.items)
        },
        toCents(value) {
            return Math.round(value * 100)
        },
        fromCents(value) {
            return value / 100
        },
        useVat(items) {
            let totalNet = 0
            let totalVat = 0
            let totalGross = 0

            items.forEach(item => {
                const priceCents = this.toCents(item.price)
                const qty = item.amount
                const rate = item.vat

                let net = 0
                let vat = 0
                let gross = 0

                if (this.includedVat) {
                    console.log('with VAT')
                    // with VAT
                    gross = priceCents * qty
                    net = Math.round(gross / (1 + rate / 100))
                    vat = gross - net
                } else {
                    console.log('without VAT')
                    // without VAT
                    net = priceCents * qty
                    vat = Math.round(net * rate / 100)
                    gross = net + vat
                }

                totalNet += net
                totalVat += vat
                totalGross += gross
            })

            this.invoice.total_price = totalNet
            this.invoice.total_price_vat = totalVat
            this.invoice.total_price_without_vat = totalGross

            return {
                totalNet: this.fromCents(totalNet),
                totalVat: this.fromCents(totalVat),
                totalGross: this.fromCents(totalGross)
            }
        }
    }
}
</script>
