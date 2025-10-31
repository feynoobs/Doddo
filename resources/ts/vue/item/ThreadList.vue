<template>
    <div>
        <router-link v-bind:to="{name: 'group'}" class="back">板一覧に戻る</router-link>
    </div>
    <div class="inner_wrap" v-if="props.data !== undefined">
        <div class="gname">{{ props.data!.board.name }}</div>
        <ul class="group">
            <li v-for="(v, k) in props.data!.threads" v-bind:key="k" class="link"><router-link v-bind:to="{name: 'thread', params:{id: v.id}}">{{ v.name }}（{{v.responses_count}}）</router-link>&nbsp;</li>
        </ul>
    </div>
</template>

<style scoped>
.back {
    color: #333;
    background-color: #fff;
    text-decoration: none;
    padding: 4px 8px;
    transition: all 0.3s ease;
    border-radius: 8px;
    display: flex;
    justify-content: center;
    align-items: center; 
    height: 40px;
    width: 180px;
}

.back:hover {
    color: #fff;
    background-color: #333;
}

div.gname {
    font-size: 24px;
    color: #333;
    margin: 20px 0 10px 0;
}
li.link {
    line-height: 1.3;
    display: inline;
    text-decoration: underline;
    padding-right: 5px;
    color: #333;
}
li.link:hover {
    color: blue;
}
div.inner_wrap {
    margin: 20px 10px;
}
</style>

<script setup lang="ts">
const props = defineProps({
    data: Object,
});
</script>