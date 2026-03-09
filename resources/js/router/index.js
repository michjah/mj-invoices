import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/Home/Home.vue';
import Contacts from '../components/Contacts/Contacts.vue';
import Contact from "../components/Contacts/Contact.vue";
import Ix from "../components/Invoices/Invoices.vue";
import NotFound from "../components/NotFound/NotFound.vue";
import NewContact from "../components/Contacts/NewContact.vue";
import NewInvoice from "../components/Invoices/NewInvoice.vue";
import Invoice from "../components/Invoices/Invoice.vue";
import InvoiceUpdate from "../components/Invoices/InvoiceUpdate.vue";

const routes = [
    { path: '/', name: 'Home', component: Home },
    { path: '/contacts', name: 'Contacts', component: Contacts },
    { path: '/contacts/:id', name: 'Contact', component: Contact },
    { path: '/contacts/new', name: 'NewContact', component: NewContact, props: { newContact: true }},
    { path: '/invoices', name: 'Invoices', component: Ix },
    { path: '/invoices/new', name: 'NewInvoice', component: NewInvoice },
    { path: '/invoices/:id', name: 'Invoice', component: Invoice },
    { path: '/invoices/:id/update', name: 'InvoiceUpdate', component: InvoiceUpdate},

    { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
