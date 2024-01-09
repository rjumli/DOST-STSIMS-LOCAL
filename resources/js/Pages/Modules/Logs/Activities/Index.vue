<template lang="">
    <Head title="Activity Logs" />
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
                            <th style="width: 25%;">Subject</th>
                            <th style="width: 25%;"  class="text-center">Description</th>
                            <th style="width: 17%;" class="text-center">User</th>
                            <th style="width: 15%;" class="text-center">Date</th>
                            <th style="width: 15%;" class="text-center">Type</th>
                            <th style="width: 17%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(list,index) in lists" v-bind:key="index">
                            <td>
                                <div class="flex-shrink-0 avatar-xs">
                                    <div class="avatar-title bg-light text-primary rounded-3 fs-15">
                                        <i v-if="list.event == 'created'" class="ri-add-circle-fill text-success"></i>
                                        <i v-else class="ri-edit-box-fill text-warning"></i>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <h5 class="fs-13 mb-0 text-dark">{{list.log_name}}</h5>
                            </td>
                            <td  class="text-center">
                                <p class="fs-12 text-muted mb-0">{{ list.description }}</p>
                            </td>
                            <td class="text-center">{{list.name}}</td>
                            <td class="text-center fs-12">{{list.created_at}}</td>
                            <td class="text-center">
                                <span :class="(list.event == 'created') ? 'badge bg-success' : 'badge bg-info'">
                                    <span v-if="list.event == 'created'">Created</span>
                                    <span v-else>Updated</span>
                                </span>
                            </td>
                            <td>
                                <b-button variant="soft-primary" @click="openView(list)" v-b-tooltip.hover title="View Activity" size="sm" class="remove-list me-1">
                                    <i class="ri-eye-fill align-bottom"></i>
                                </b-button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <Pagination class="ms-2 me-2" v-if="meta" @fetch="fetch" :lists="lists.length" :links="links" :pagination="meta" />
            </div>
        </div>
    </div>
    <View ref="view"/>
</template>
<script>
import View from './View.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
import PageHeader from "@/Shared/Components/PageHeader.vue";
export default {
    components : { PageHeader, Pagination, View },
    data(){
        return {
            currentUrl: window.location.origin,
            title: "Activity Logs",
            items: [{text: "Logs",href: "/"},{text: "Activity",active: true}],
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
            page_url = page_url || '/logs/activities';
            axios.get(page_url,{
                params : {
                    counts: ((window.innerHeight-450)/56),
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
        openView(data){
            this.$refs.view.set(data);
        }
    }
}
</script>