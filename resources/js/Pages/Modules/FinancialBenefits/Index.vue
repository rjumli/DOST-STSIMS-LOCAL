<template>
    <Head title="Financial Benefits" />
    <PageHeader :title="title" :items="items" />
     <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="file-manager-sidebar">
            <Sidebar :latest="latest" ref="sidebar"/>
        </div>
        <div class="file-manager-content w-100 p-4 pb-0" style="height: calc(100vh - 180px); overflow: auto;" ref="box">
            <Lists v-if="show == 'default'" ref="lists"/>
            <Generate :latest="latest" v-else ref="generate"/>
        </div>
    </div>
</template>
<script>
import Lists from './Pages/Lists.vue';
import Generate from './Pages/Generate.vue';
import Sidebar from './Sidebar.vue';
import PageHeader from "@/Shared/Components/PageHeader.vue";
export default {
    components : { PageHeader, Sidebar, Lists, Generate },
    props: ['latest'],
    data() {
        return {
            currentUrl: window.location.origin,
            title: "Financial Benefits",
            items: [{text: "Financial Benefits",href: "/"},{text: "List",active: true}],
            show: 'default',
        };
    },
    mounted(){
        this.$refs.sidebar.set(this.latest);
    },
    computed: {
        data() {
            return this.$page.props.flash;
        }
    },
    watch: {
        data: {
            deep: true,
            handler(val = null) {
                if(val != null && val !== ''){
                    switch(val.type){
                        case 'completed':
                            this.$nextTick(function(){this.$refs.lists.fetch()});
                        break;
                        case 'pending':
                            
                        break;
                    }
                }
            },
        },
        latest: {
            deep: true,
            handler(newLatest) {
               this.$refs.sidebar.set(newLatest);
            },
        },
    },
    methods: {
        showPage(data){
            this.show = data;
            (data == 'generate') ?  this.$nextTick(function(){this.$refs.generate.fetch()}) : '';
        }
    }
}
</script>