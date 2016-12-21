<template>
    <div class="modal fade" id="modal" tabindex="-1" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 id="modal-title" class="modal-title"></h4>
          </div>

          <div class="modal-body" id="modal-body">
          </div>

          <div class="modal-footer">
              <button type="submit" id="modal-update" class="btn btn-primary">
                  <i class="fa fa-btn fa-check"></i>  はい
              </button>
              <button type="button" class="btn btn-default" data-dismiss="modal">
                  <i class="fa fa-btn fa-close"></i>いいえ</button>

          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</template>
