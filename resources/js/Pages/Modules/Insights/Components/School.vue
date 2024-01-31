<template>
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h5 class="card-title mb-0 flex-grow-1">Most Schools</h5>
            <div>
                <!-- <button class="btn btn-soft-primary btn-sm" type="button">
                    <div @click="viewSchools" class="btn-content"> View all </div>
                </button> -->
                <div class="input-group input-group-sm">
                    <select v-model="scholars" @change="fetch('desc')" class="form-select" id="inputGroupSelect01" style="width: 170px;">
                        <option :value="null">All Scholars</option>
                        <option value="ongoing">Ongoing Scholars</option>
                        <option value="graduated">Graduated Scholars</option>
                    </select>
                    <b-button type="button" variant="primary">
                        View
                    </b-button>
                </div>
            </div>
        </div>
        <div class="card-body" style="height: 300px;">
            <div class="table-responsive table-card">
                <table class="table align-middle table-centered table-nowrap mb-3">
                    <thead class="text-muted table-light fs-11">
                        <tr>
                            <th style="cursor: pointer;">  
                                <i @click="fetch('asc')" v-if="sort == 'desc'" class="ri-sort-asc"></i> 
                                <i @click="fetch('desc')" v-else class="ri-sort-desc"></i> 
                            </th>
                            <th scope="col">School</th>
                            <th class="text-center" scope="col">#</th>
                            <th class="text-center" scope="col">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(list,index) in schools" v-bind:key="index">
                            <td>{{index + 1}}</td>
                            <td>{{ucwords(list.school.name)}}</td>
                            <td class="text-center">{{list.scholars_count}} </td>
                            <td class="text-center">{{percentage(list.scholars_count)}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['total'],
    data(){
        return {
            currentUrl: window.location.origin,
            schools: [],
            sort: null,
            scholars: null,
        }
    },
    created(){
        this.fetch('desc');
    },
    methods : {
        fetch(sort) {
            this.sort = sort;
            axios.get(this.currentUrl + '/insights', {
                params: {
                    type: 'schools',
                    sort: this.sort,
                    scholars: this.scholars
                }
            })
            .then(response => {
                this.schools = response.data;
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
        viewSchools(){
            this.$refs.schools.set();
        }
    }
}
</script>