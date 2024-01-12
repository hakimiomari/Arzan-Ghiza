import { ref, watch } from "vue";

export function useImageUpload() {
  const photo = ref("");
  const imgUrl = ref("");

  const fileChange = (event) => {
    if (event.target.files.length === 0) {
      imgUrl.value = "";
      photo.value = "";
      return;
    }
    photo.value = event.target.files[0];
  };

  watch(photo, (photo) => {
    if (!photo) {
      return;
    }

    let fileReader = new FileReader();

    fileReader.readAsDataURL(photo);

    fileReader.addEventListener("load", () => {
      imgUrl.value = fileReader.result;
    });
  });

  return {
    fileChange,
    imgUrl,
    photo,
  };
}
