<template>
    <Head title="Qualifiers" />
    <PageHeader :title="title" :items="items" />
     <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1" v-if="status == true">
        <div class="file-manager-sidebar">
            <Sidebar ref="sidebar"/>
        </div>
        <div class="file-manager-content w-100 p-4 pb-0" style="height: calc(100vh - 180px); overflow: auto;" ref="box">
            <List @status="fetchUpdate()" :dropdowns="dropdowns" :status_list="status_list" :program_list="program_list" :subprogram_list="subprogram_list" :regions="regions.data"/>
        </div>
    </div>
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1" v-else>
        <div class="file-manager-content w-100 p-4 pb-0 d-flex justify-content-center align-items-center" style="height: calc(100vh - 180px);" ref="box">
            <div class="alert alert-danger mb-xl-0 text-center" role="alert" style="width: 40%;"  v-if="status == 'Unauthorized'"><b>Unauthorized</b>. Please contact Administrator, Thanks. </div>
            <div class="alert alert-danger mb-xl-0 text-center" role="alert" style="width: 40%;" v-else-if="status == 404"><b>API not found</b>. Please contact Administrator, Thanks.</div>
        </div>
    </div>
</template>
<script>
import List from './Lists.vue';
import Sidebar from './Sidebar.vue';
import PageHeader from "@/Shared/Components/PageHeader.vue";
export default {
    props: ['dropdowns','programs','regions','statuses'],
    components : { PageHeader, List, Sidebar },
    data() {
        return {
            currentUrl: window.location.origin,
            title: "List of Qualifiers",
            items: [{text: "Qualifiers",href: "/"},{text: "List",active: true}],
            status: '',
        };
    },
    created(){
        this.checkApi();
    },
    computed: {
        program_list : function() {
            return this.programs.data.filter(x => x.is_sub === 1).filter(x => x.is_active === 1);
        },
        subprogram_list : function() {
            return this.programs.data; 
        },
        status_list : function() {
            return this.statuses.data.filter(x => x.type == 'Qualifier');
        }
    },
    methods : {
        checkApi() {
            axios.get(this.currentUrl + '/sync/check')
            .then(response => {
                this.status = response.data.status;
            })
            .catch(err => {
                if(err.response.status == 404){
                    this.status = err.response.status;
                }
            });
        },
        fetchUpdate(){
            this.$refs.sidebar.fetch();
        }
    }
}
</script>