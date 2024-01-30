<template>
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h5 class="card-title mb-0 flex-grow-1">Provinces</h5>
            <div>
                <button @click="view()" class="btn btn-soft-primary btn-sm" type="button">
                    <div class="btn-content"> View all </div>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <simplebar data-simplebar style="height: 358px;">
                    <table class="table table-centered table-bordered table-nowrap mb-0">
                        <thead class="table-light thead-fixed">
                            <tr class="text-muted fs-10">
                                <th width="40%" scope="col">Province</th>
                                <th width="15%" style="cursor: pointer;" class="text-center" v-for="(list,index) in provinces.programs" v-bind:key="index">{{list.name}}</th>
                                <th width="15%" class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody class="fs-11 ">
                            <tr v-for="(list,index) in provinces.provinces" v-bind:key="index" :class="(list.region_code == region_code) ? 'text-info' : ''">
                                <td @click="view(list.code)" style="cursor:pointer;" width="40%" class="fw-medium">{{list.province}}</td>
                                <td width="15%" class="text-center" v-for="(count,index) in list.count" v-bind:key="index">{{count}} </td>
                                <td width="15%" class="text-center fw-bold">{{list.total}}</td>
                            </tr>
                        </tbody>
                        <tfoot class="text-muted table-light fs-10 tfoot-fixed">
                            <tr>
                                <th style="width: 40%;">Total</th>
                                <th style="width: 15%;" class="text-center" v-for="(count,index) in provinces.totals.count" v-bind:key="index">{{count}}</th>
                                <th style="width: 15%;" class="text-center">{{provinces.totals.total}}</th>
                            </tr>
                        </tfoot>
                    </table>
                </simplebar>
            </div>
        </div>
    </div>
</template>
<script>
import simplebar from 'simplebar-vue';
export default {
    components: { simplebar },
    props: ['provinces','total','region_code'],
    data(){
        return {
            height: window.innerHeight - 557,
        }
    },
    methods : {
        ucwords(str) {
            let str1 = str.toLowerCase().trim();
            return (str1 + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
                return $1.toUpperCase();
            });
        },
        percentage(data){
            return (_.divide(data, this.total)*100).toFixed(2)+'%';
        },
        view(list){
            this.$refs.district.set(list);
        }
    }
}
</script>
<style>
.thead-fixed {
   position: sticky;
   top: 0;
   background-color: #fff; /* Set the background color for the fixed header */
   z-index: 1; /* Ensure the fixed header is above the scrolling content */
}
.tfoot-fixed {
   position: sticky;
   bottom: 0;
   background-color: #fff; /* Set the background color for the fixed header */
   z-index: 1; /* Ensure the fixed header is above the scrolling content */
}
</style>