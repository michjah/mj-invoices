<template>
    <v-data-table-server
        v-model:items-per-page="totalItems"
        v-model:sort-by="sortBy"
        :headers="headers"
        :items="serverItems"
        :items-length="totalItems"
        @update:options="loadItems"
    >
        <template v-slot:bottom>
            <div class="text-center pt-2">
                <v-pagination
                    v-model="p"
                ></v-pagination>
            </div>
        </template>

        <template v-slot:item.action="{ item }">
            <v-btn density="compact" :loading="item.loading" elevation="0" icon color="green" v-on:click="console.log(item)">
                <v-icon size="12" dark>mdi-pencil</v-icon>
            </v-btn>
        </template>
    </v-data-table-server>
</template>

<script>
import axios from "axios";

export default {
    name: 'mjtable',
    data() {
        return {
            serverItems: [],
            totalItems: 0,
            countPages: 1,
            loading: false,
            sortBy: [{ key: 'name', order: 'desc' }],
            p: 1
        }
    },
    props: [
        'url',
        'headers',
        'options',
        'itemsPerPage',
        'page'
    ],
    methods: {
        loadItems (options) {
            let self = this
            self.loading = true

            const opt = {
                'page': options.page,
                'sortBy': options.sortBy.key ?? self.sortBy,
                'sortDesc': self.sortBy.order ?? self.sortDesc
            }
            axios.get(self.url, {params: opt})
                .then((response) => {
                    self.serverItems = response.data.data
                    self.totalItems = response.data.total

                    self.countPages = self.totalItems / self.itemsPerPage
                    self.loading = false
                })
        },
    }
}
</script>
