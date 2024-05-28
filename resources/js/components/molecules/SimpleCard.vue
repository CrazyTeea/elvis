<script>
export default {
    name: "SimpleCard"
}
</script>

<template>
    <div class="card" >
        <div class="card-body">
            <h5 class="card-title">Card title</h5>
            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            <a href="#" class="btn btn-primary">Go somewhere</a>
        </div>
    </div>
</template>

<style scoped>
.card {
    border: 0.5px solid;
    border-image-source: linear-gradient(180deg, #424750 0%, #202326 100%);
    box-shadow: 6px 6px 10px 0px rgba(16, 16, 18, 0.5);

}
.card-body {

}
</style>
