/**
 * Add more forms v0.0.1
 * Author: Satpal Bhardwaj
 * Description: Add more forms, change each input naming and grouping
 *
 * options: {
 *  disable-naming: boolean,
 *  wrapper: string,
 *  group: string,
 *  add-btn: string,
 *  remove-btn: string
 * }
 */

(function ($) {
  'use strict';

  const trimAttr = (attr) => attr.replace('[', '').replace(']', '');

  const setupInputNames = (wrapper, group, arrayName, disableNaming, current_index, is_edit) => {

    $(wrapper).find(group).each((index, element) => {
      // add index to the group
      $(element).attr(trimAttr(group), index);
      // change the input naming to array

      $(element)
        .find('input, select,.calender_div')
        .each((ix, element) => {
          if (typeof $(element).attr(trimAttr(disableNaming)) === 'undefined') {
            const name = getInputName(element);
            $(element).attr('name', `${arrayName}[${index}][${name}]`);
            var id = $(element).attr('id', `${arrayName}[${index}][${name}]`);
            if (index > current_index) {
              if ($(element).attr('data-name') == 'id') {

                $(element).remove();
              }
              if (is_edit) {

                is_edit = false;
              }
            }

            if ($(element).attr('data-name') == 'scheme_identification') {
              // $(element).removeClass('select2');
              // $(element).removeClass('select2-hidden-accessible');
              // console.log($(element).parent('div').find('span.select2'));
              // $(element).parent('div').find('span.select2').remove();
              // $(element).addClass('select2');
              // var scheme_id = $('#scheme_id').val();
              // $(element).val(scheme_id + '-' + (index+1));
              if (is_edit == false) {

                scheme_identification(index);
              } else {

                if (index > current_index) {

                  scheme_identification(index);
                }
              }


            }
            if ($(element).attr('data-name') == 'scheme_type_id') {
              $(element).select2();
            }

            if ($(element).attr('data-name') == 'calender_date') {


              initializeDatepicker($(element));

              if ($(element).find('div.datepicker.datepicker-inline').length > 1) {
                $(element).find('div.datepicker.datepicker-inline:first').remove();
              }

              dates();

            }
          }
        });
    });



    // var FromEndDate = new Date();
    // $(wrapper).find(".datepicker").datepicker({
    //   format: 'dd-mm-yyyy',
    //   endDate: FromEndDate,
    // });
    // console.log($(wrapper).find(".datepicker"));
    // }, 1500);
    // $(wrapper).find(".to_datepicker").datepicker({
    //   format: 'dd-mm-yyyy',
    //   endDate: FromEndDate,

    // });

  };

  const getInputName = (input) => {
    let name = $(input).attr('data-name');

    if (!name) {
      name = $(input).attr('name');
      $(input).attr('data-name', name);
    }

    return name;
  };

  $.fn.replicate = function (options = {}) {
    const { disableNaming, wrapper, group, addBtn, removeBtn } = options;

    if (!wrapper || !group || !addBtn || !removeBtn) {
      throw new Error('Missing required options');
    }

    const arrayName = $(this).attr(trimAttr(wrapper));

    setupInputNames(this, group, arrayName, disableNaming, current_index, is_edit);

    if ($(wrapper).data('x-wrapper') == 'duration') {
      remove_btn($('.remove'));
      // removePlusButton();
    }

    $(document).on('click', addBtn, function () {
      const wrap = $(this).closest(wrapper);
      const allGroups = $(wrap).find(group);
      if (allGroups.length >= total_rows) {
        // alert('Max 5');
        $('#alertModal').modal('show').find('.modal-body').text('Maximum ' + total_rows + ' item are allowed !');
        return;
      }
      const newGroup = $(this).closest(group).clone();
      // console.log($(this).attr('data-attendance-edit-plus'));
      const attr = $(this).attr('data-attendance-edit-plus');
      if (typeof attr !== 'undefined' && attr !== false) {
        const picture = newGroup.find('.picture-div');
        const newPicturediv = $('.picture-field')
        newPicturediv.removeAttr('hidden');
        picture.replaceWith(newPicturediv);
      }

      newGroup.find('input:not(:radio), select').val('');
      newGroup.find('input:radio, input:checkbox').prop('checked', false);

      $(newGroup).insertAfter($(this).closest(group));
      // Attendance/Wage condition 
      if ($(wrapper).data('x-wrapper') == 'duration') {
        remove_btn($('.remove'));
        removeSelect('.scheme_prior_id');
        // removePlusBtn();
        removePlusButton();
        checkingVerified();
      }
      // -

      setupInputNames($(this).closest(wrapper), group, arrayName, disableNaming, current_index, is_edit);



    });

    $(document).on('click', removeBtn, function () {
      //alert('test');
      const wrap = $(this).closest(wrapper);
      // console.log(wrap);


      const allGroups = $(wrap).find(group);
      //console.log(allGroups);

      if (allGroups.length <= 1) {
        // alert('At least one item is required!');
        $('#alertModal').modal('show').find('.modal-body').text('At least one item is required!');

        return;
      }

      $(this).closest(group).remove();

      setupInputNames($(wrap), group, arrayName, disableNaming, current_index, is_edit);
    });
  };
})(jQuery);
