<template>
    <div class="p-1 border border-dashed bg-gray rounded mb-3 mt-n2">
        <div class="d-flex align-items-center">
            <div class="avatar-sm me-2">
                <div class="avatar-title rounded bg-transparent text-primary fs-24"><i class="ri-calendar-fill"></i></div>
            </div>
            <div class="flex-grow-1">
                <h5 class="fs-16 text-primary fw-semibold mt-1 ms-n2 mb-0">LIST OF SCHOLARS</h5>
            </div>
            <div class="text-end">
                <button @click="openConfirm()" class="btn btn-md btn-light btn-label waves-effect waves-light" type="button">
                    <i class="ri-hand-coin-fill label-icon align-middle fs-16 me-2"></i> Release Benefits
                </button>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <simplebar data-simplebar style="height: calc(100vh - 300px);">
            <table class="table table-bordered table-nowrap align-middle mb-0">
                <thead class="table-light thead-fixed">
                    <tr class="fs-13">
                        <th class="text-center bg-dark fs-bold text-light text-uppercase" colspan="5">MONTH OF {{latest.month}} - BATCH {{latest.count+1}}</th>
                    </tr>
                    <tr class="fs-11">
                        <th width="5%" class="text-center"></th>
                        <th width="30%" class="text-center">Scholar</th>
                        <th width="30%" class="text-center">Account no.</th>
                        <th width="30%" class="text-center">Amount</th>
                        <th width="5%" class="text-center"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(list,index) in lists" v-bind:key="index">
                        <td class="text-center">{{index+1}}</td>
                        <td class="text-center">{{list.name}}</td>
                        <td class="text-center">{{list.account_no}}</td>
                        <td class="text-center">{{ formatMoney(list.total) }}</td>
                        <td class="text-center">
                            <b-button @click="openView(list)" variant="soft-info" v-b-tooltip.hover title="View" size="sm" class="edit-list"><i class="ri-eye-fill align-bottom"></i> </b-button>
                        </td>
                    </tr>
                </tbody>
                <tfoot class="table-light tfoot-fixed">
                    <tr class="fs-12 fw-semibold">
                        <th></th>
                        <th class="text-center">{{lists.length}} Scholars</th>
                        <th></th>
                        <th class="text-center">{{this.formatMoney(total)}}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </simplebar>
    </div>
    <View ref="view"/>
    <Confirm ref="confirm"/>
</template>
<script>
import View from '../Modals/View.vue';
import Confirm from '../Modals/Confirm.vue';
import simplebar from 'simplebar-vue';
export default {
    components: { simplebar, View, Confirm },
    props: ['latest'],
    data(){
        return {
            lists: []
        }
    },
    computed: {
        total: function () {
            var sum = 0;
            if(this.lists != undefined){
                this.lists.forEach(e => {
                    sum += Number(e.total);
                });
            }
            return sum;
        }
    },
    methods: {
        fetch() {
            axios.get('/financial-benefits',{
                params : {
                    info : JSON.stringify(this.latest.pending),
                    option: 'benefits'
                }
            })
            .then(response => {
                this.lists = _.orderBy(response.data.data, 'name', 'asc');
            })
            .catch(err => console.log(err));
        },
        openView(data){
            this.$refs.view.set(data);
        },
        openConfirm(){
            this.$refs.confirm.set(this.lists,this.latest,this.total);
        },
        formatMoney: function formatMoney(value) {
            var val = (value / 1).toFixed(2).replace(',', '.');
            return '₱' + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    }
}
</script>
<style>
.thead-fixed {
   position: sticky;
   top: 0;
   background-color: #fff; 
   z-index: 1;
}
.tfoot-fixed {
   position: sticky;
   bottom: 0;
   background-color: #fff;
   z-index: 1;
}
</style>