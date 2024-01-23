<template>
    <b-row class="g-2 mb-2 mt-n2">
        <b-col lg>
            <div class="input-group mb-1">
                <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                <input type="text" placeholder="Search" class="form-control" style="width: 40%;">
                <span @click="refresh" class="input-group-text" v-b-tooltip.hover title="Refresh" style="cursor: pointer;"> 
                    <i class="bx bx-refresh search-icon"></i>
                </span>
                <b-button type="button" variant="primary">
                    <i class="ri-filter-fill align-bottom me-1"></i> Filter
                </b-button>
            </div>
        </b-col>
    </b-row>
    <div class="table-responsive">
        <table class="table table-nowrap align-middle mb-0">
            <thead class="table-light">
                <tr class="fs-11">
                    <th>Release Month</th>
                    <th class="text-center">Receiver</th>
                    <th class="text-center">Total</th>
                    <th class="text-center">Added By</th>
                    <th class="text-center">Released Date</th>
                    <th class="text-center">Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                        <tr v-for="(list,index) in lists" v-bind:key="list.id">
                            <td>
                                <h5 class="fs-13 mb-0 text-dark">{{list.month}}</h5>
                                <p class="fs-12 text-muted mb-0">Batch {{list.batch }}</p>
                            </td>
                            <td class="text-center">{{list.lists.length}} Scholar<span v-if="list.lists.length > 1">s</span></td>
                            <td class="text-center"> ₱{{ formatMoney(list.total) }}</td>
                            <td class="text-center">{{list.added_by.firstname}} {{list.added_by.lastname}}</td>
                            <td class="text-center">{{list.created_at}}</td>
                            <td class="text-center">
                                <span :class="'badge '+list.status.color">{{list.status.name}}</span>
                            </td>
                            <td class="text-end">
                                <b-button @click="print(list.id)" variant="soft-primary" v-b-tooltip.hover title="Print" size="sm" class="edit-list me-1"><i class="ri-printer-fill align-bottom"></i> </b-button>
                                <b-button @click="viewLbp(list)" variant="soft-warning" v-b-tooltip.hover title="View LBP" size="sm" class="edit-list me-1"><i class="ri-bank-line align-bottom"></i> </b-button>
                                <b-button @click="viewList(list)" variant="soft-info" v-b-tooltip.hover title="View" size="sm" class="edit-list me-1"><i class="ri-eye-fill align-bottom"></i> </b-button>
                                <b-button v-if="list.status.name != 'Released'" @click="viewConfirmation(list,index)" variant="soft-success" v-b-tooltip.hover title="Mark as Completed" size="sm" class="edit-list me-1"><i class="ri-checkbox-circle-fill align-bottom"></i> </b-button>
                            </td>
                        </tr>
                    </tbody>
        </table>
        <Pagination class="ms-2 me-2" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
    </div>
</template>
<script>
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { Pagination },
    data(){
        return {
            currentUrl: window.location.origin,
            lists: [],
            meta: {},
            links: {},
            filter: {
                keyword: null,
                status: null
            }
        }
    },
    created(){
        this.fetch();
    },
    methods: {
        checkSearchStr: _.debounce(function(string) {
            this.fetch();
        }, 300),
        fetch(page_url) {
            page_url = page_url || '/financial-benefits';
            axios.get(page_url, {
                params: {
                    keyword: this.filter.keyword,
                    status: this.filter.status,
                    counts: parseInt(((window.innerHeight-350)/56)),
                    option: 'lists'
                }
            })
            .then(response => {
                this.lists = response.data.data;
                this.meta = response.data.meta;
                this.links = response.data.links;
            })
            .catch(err => console.log(err));
        },
         formatMoney: function formatMoney(value) {
            var val = (value / 1).toFixed(2).replace(',', '.');
            return '₱' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
    }
}
</script>