<template lang="">
    <Head title="Authentication Logs" />
    <PageHeader :title="title" :items="items" />
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="file-manager-content w-100 p-4 pb-0" style="height: calc(100vh - 180px); overflow: auto;" ref="box">
            <b-row class="g-2 mb-2 mt-n2">
                <b-col lg>
                    <div class="input-group mb-1">
                        <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                        <input type="text" v-model="keyword" placeholder="Search Logs" class="form-control" style="width: 35%;">
                        <span @click="refresh" class="input-group-text" v-b-tooltip.hover title="Refresh" style="cursor: pointer;"> 
                            <i class="bx bx-refresh search-icon"></i>
                        </span>
                        <b-button type="button" variant="primary" @click="openCreate">
                            <i class="ri-add-circle-fill align-bottom me-1"></i> Search
                        </b-button>
                    </div>
                </b-col>
            </b-row>
            <div class="table-responsive">
                <table class="table table-nowrap align-middle mb-0">
                    <thead class="table-light">
                        <tr class="fs-11">
                            <th></th>
                            <th style="width: 25%;">Browser</th>
                            <th style="width: 11%;" class="text-center">IP address</th>
                            <th style="width: 15%;" class="text-center">Login Date</th>
                            <th style="width: 15%;" class="text-center">Logout Date</th>
                            <th style="width: 15%;" class="text-center">Type</th>
                            <th style="width: 17%;" class="text-center">User</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(list,index) in lists" v-bind:key="index">
                            <td>
                                <div class="flex-shrink-0 avatar-xs">
                                    <div class="avatar-title bg-light text-primary rounded-3 fs-15">
                                        <i :class="'ri-'+list.type+'-fill '+list.attempt"></i>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <h5 class="fs-13 mb-0 text-dark">{{list.platform}} ({{list.browser}})</h5>
                                <p v-if="list.location" class="fs-12 text-muted mb-0">{{ list.location.city }},  {{ list.location.state_name }}, {{ list.location.country }}</p>
                                <p v-else class="fs-12 text-muted mb-0">Not Available</p>
                            </td>
                            <td class="text-center fs-12 fw-semibold">{{list.ip}}</td>
                            <td class="text-center fs-12">{{(list.login_at) ? list.login_at : 'n/a'}}</td>
                            <td class="text-center fs-12">{{(list.logout_at) ? list.logout_at : 'n/a'}}</td>
                            <td class="text-center">
                                <span v-if="list.logout_at == 'n/a'" :class="(list.is_attempt) ? 'badge bg-success' : 'badge bg-danger'">
                                    <span v-if="list.is_attempt">Login Successful</span>
                                    <span v-else>Login Failed</span>
                                </span>
                                <span v-else class="badge bg-warning">Logout</span>
                            </td>
                            <td class="text-center">
                                
                                        <h5 class="fs-13 mb-0 text-dark">{{list.name}}</h5>
                                        <span class="fs-12 text-muted">{{list.role}}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <Pagination class="ms-2 me-2" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
            </div>
        </div>
    </div>
</template>
<script>
import Pagination from "@/Shared/Components/Pagination.vue";
import PageHeader from "@/Shared/Components/PageHeader.vue";
export default {
    components : { PageHeader, Pagination },
    data(){
        return {
            currentUrl: window.location.origin,
            title: "Authentication Logs",
            items: [{text: "Logs",href: "/"},{text: "Authentication",active: true}],
            lists: [],
            meta: {},
            links: {},
            keyword: null
        }
    },
    created(){
        this.fetch();
    },
    methods: {
        fetch(page_url){
            page_url = page_url || '/logs/authentication';
            axios.get(page_url,{
                params : {
                    counts: ((window.innerHeight-360)/60),
                    option: 'lists'
                }
            })
            .then(response => {
                if(response){
                    this.lists = response.data.data;
                    this.meta = response.data.meta;
                    this.links = response.data.links;          
                }
            })
            .catch(err => console.log(err));
        },
    }
}
</script>