<template>
  <ScanComponent :title="title" :url-api="urlApi"></ScanComponent>
</template>

<script>
import ScanComponent from "@/app/Monitaz/Components/ScanComponent";
export default {
  components: {ScanComponent},
  data() {
    return {
      title: "Scan Page",
      urlApi: "/api/scan-page"
    }
  }
}
</script>
