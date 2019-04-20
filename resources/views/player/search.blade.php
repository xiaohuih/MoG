<div class="box">
    <div class="box-header with-border">
        <form class="form-inline">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-btn">
                        <select class="form-control" id="searchKeySelect">
                            <option value="id">{{ trans('game.player.id') }}</option>
                            <option value="name">{{ trans('game.player.name') }}</option>
                        </select>
                    </span>
                    <input type="text" class="form-control" id="searchValue">
                </div>
            </div>
            <button type="submit" class="btn btn-success"><i class="fa fa-search"></i></button>
        </form>
    </div>
</div>