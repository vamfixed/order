<template>
    <div>
        <div class="row">
            <div class="col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fa fa-envelope"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Messages</span>
                        <span class="info-box-number">1,410</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-sm-6 col-12">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fa fa-flag"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Bookmarks</span>
                        <span class="info-box-number">410</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            
            <!-- /.col -->
        </div>
        <div class="row justify-content-center">
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3" v-for="i in 10" :key="i">
                <div class="card">
                    <div class="card-header">Order # {{ i }}</div>
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">This is a wider card with supporting text below as a natural lead-in to
                            additional content. This content is a little bit longer.</p>
                        <button class="btn btn-xs btn-success">Close</button>
                    </div>
                    <div class="card-footer">
                        <small class="text-muted">Last updated 3 mins ago</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        created() {
            axios.get('/api/order/getOrders')
            .then(res => {})
            .catch(error => {});
        },
        mounted() {
            console.log('Component mounted.')
        }
    }

</script>
