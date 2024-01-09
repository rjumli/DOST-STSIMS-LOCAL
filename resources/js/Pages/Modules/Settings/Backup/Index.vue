<template lang="">
    <Head title="Backup and Restore" />
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
                            <i class="ri-database-2-fill align-bottom me-1"></i> Generate
                        </b-button>
                    </div>
                </b-col>
            </b-row>
            <div class="table-responsive">
                <table class="table table-nowrap align-middle mb-0">
                    <thead class="table-light">
                        <tr class="fs-11">
                            <th style="width: 5%;"></th>
                            <th style="width: 30%;">Name</th>
                            <th style="width: 30%;"  class="text-center">Path</th>
                            <th style="width: 15%;" class="text-center">Size</th>
                            <th style="width: 15%;" class="text-center">Date</th>
                            <th style="width: 5%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(list,index) in lists" v-bind:key="index">
                            <td>
                               {{index+1}}
                            </td>
                            <td>
                                <h5 class="fs-13 mb-0 text-dark">{{list.name}}</h5>
                            </td>
                            <td  class="text-center">
                                <p class="fs-12 text-muted mb-0">{{ list.path }}</p>
                            </td>
                            <td class="text-center">{{list.size}}</td>
                            <td class="text-center fs-12">{{list.date}}</td>
                            <td>
                                <b-button @click="downloadFile(list.name)" pill variant="primary" class="btn-label btn-sm waves-effect waves-light">
                                    <i class="ri-download-cloud-2-fill label-icon align-middle rounded-pill fs-12 me-2"></i> Download &nbsp;&nbsp;
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
import Pagination from "@/Shared/Components/Pagination.vue";
import PageHeader from "@/Shared/Components/PageHeader.vue";
export default {
    components : { PageHeader, Pagination },
    data(){
        return {
            currentUrl: window.location.origin,
            title: "Backup and Restore",
            items: [{text: "Settings",href: "/"},{text: "Backup and Restore",active: true}],
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
            page_url = page_url || '/settings/backups';
            axios.get(page_url,{
                params : {
                    counts: ((window.innerHeight-350)/56),
                    option: 'lists'
                }
            })
            .then(response => {
                if(response){
                    this.lists = response.data;
                    // this.meta = response.data.meta;
                    // this.links = response.data.links;          
                }
            })
            .catch(err => console.log(err));
        },
        openView(data){
            this.$refs.view.set(data);
        },
        downloadFile(filePath) {
            window.open(this.currentUrl+'/settings/backups/download/'+filePath);
        },
    }
}
</script>