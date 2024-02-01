<template>
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h5 class="card-title mb-0 flex-grow-1">Most Schools</h5>
            <div>
                <div class="input-group input-group-sm">
                    <select v-model="scholars" @change="fetch()" class="form-select" id="inputGroupSelect01">
                        <option :value="null">All Scholars</option>
                        <option value="ongoing">Ongoing Scholars</option>
                        <option value="graduated">Graduated Scholars</option>
                    </select>
                    <!-- <b-button @click="openView()" type="button" variant="primary">
                        View
                    </b-button> -->
                </div>
            </div>
        </div>
        <div class="card-body" style="height: 330px;">
            <div class="table-responsive table-card" ref="cardRef">
                <table class="table align-middle table-centered table-nowrap mb-3">
                    <thead class="text-muted table-light fs-11">
                        <tr>
                            <th style="cursor: pointer; width: 40px;">  
                                <i @click="fetch('asc')" v-if="sort == 'desc'" class="ri-sort-asc"></i> 
                                <i @click="fetch('desc')" v-else class="ri-sort-desc"></i> 
                            </th>
                            <th scope="col">School</th>
                            <th class="text-center" style="width: 80px;">#</th>
                            <th class="text-center" style="width: 80px;">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(list,index) in schools" v-bind:key="index">
                            <td>{{(meta.current_page - 1) * meta.per_page + (index + 1) }}</td>
                            <td class="text-truncate" style="cursor: pointer;" :style="{ 'max-width': cardRef + 'px' }">{{ucwords(list.school.name)}}</td>
                            <td class="text-center">{{list.scholars_count}} </td>
                            <td class="text-center">{{percentage(list.scholars_count)}}</td>
                        </tr>
                    </tbody>
                </table>
                <Pagination class="ms-3 me-3" v-if="meta" @fetch="fetch" :lists="schools.length" :links="links" :pagination="meta" />
            </div>
        </div>
    </div>
    <School ref="school"/>
</template>
<script>
import Pagination from "@/Shared/Components/Pagination.vue";
import School from '../Modals/School.vue';
export default {
    components: { Pagination, School },
    props: ['total'],
    data(){
        return {
            currentUrl: window.location.origin,
            schools: [],
            meta: {},
            links: {},
            sort: 'desc',
            scholars: null,
            cardRef: null,
        }
    },
    created(){
        this.fetch();
    },
    mounted() {
        this.getCardWidth();
        window.addEventListener('resize', this.getCardWidth);
    },
    methods : {
        fetch(page_url){
            page_url = page_url || '/insights';
            axios.get(page_url, {
                params: {
                    type: 'schools',
                    sort: this.sort,
                    scholars: this.scholars
                }
            })
            .then(response => {
                this.schools = response.data.data;
                this.meta = response.data.meta;
                this.links = response.data.links;
            })
            .catch(err => console.log(err));
        },
        ucwords(str) {
            let str1 = str.toLowerCase().trim();
            return (str1 + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
                return $1.toUpperCase();
            });
        },
        percentage(data){
            return (_.divide(data, this.total)*100).toFixed(2)+'%';
        },
        openView(){
            this.$refs.schools.set();
        },
        getCardWidth() {
            const cardRef = this.$refs.cardRef;
            if (cardRef) {
                const cardWidth = cardRef.clientWidth;
                const eightyPercent = cardWidth-200;
                this.cardRef = eightyPercent;
            }
        },
    }
}
</script>