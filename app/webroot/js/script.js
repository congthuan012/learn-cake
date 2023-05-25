$(".select2").select2({});

// Lắng nghe sự kiện khi checkbox "Check All" được thay đổi
document.getElementById('checkAll').addEventListener('change', function () {
      var checkboxes = document.getElementsByClassName('checkbox-item');

      // Lặp qua tất cả các checkbox và cập nhật trạng thái checked
      for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = this.checked;
      }
});

// Lắng nghe sự kiện khi các checkbox khác được thay đổi
var checkboxes = document.getElementsByClassName('checkbox-item');
for (var i = 0; i < checkboxes.length; i++) {
      checkboxes[i].addEventListener('change', function () {
            var checkAll = document.getElementById('checkAll');

            // Kiểm tra xem tất cả các checkbox khác có được chọn hay không
            var allChecked = true;
            for (var j = 0; j < checkboxes.length; j++) {
                  if (!checkboxes[j].checked) {
                        allChecked = false;
                        break;
                  }
            }

            // Cập nhật trạng thái checked của checkbox "Check All"
            checkAll.checked = allChecked;
      });
}

//validate max min 

document.getElementById('formSearch').addEventListener('submit', function (event) {
      event.preventDefault();

      let isValid = true;
      var minInput = document.querySelector("input[name='size_min']").value;
      var maxInput = document.querySelector("input[name='size_max']").value;

      if (minInput != null && maxInput != null && Number.parseInt(minInput) > Number.parseInt(maxInput)) {
            isValid = false;

            document.querySelector('.error-min-max').innerHTML = 'Max must be less than or equal to ' + minInput;
      }else{
            document.querySelector('.error-min-max').innerHTML = '';
            isValid = true;
      }

      if (isValid) {
            this.submit();
      }
})