
@if($project->isActive())
    <span class="text-success">Активен</span>
@endif
@if($project->isOnModeration())
    <span class="text-primary">На модерации</span>
@endif
@if($project->isDraft())
    <span class="text-warning">Черновик</span>
@endif
@if($project->isDeleted())
    <span class="text-danger">Удален</span>
@endif
@if($project->isRejected())
    <span class="text-warning">Отклонен</span>
@endif
@if($project->isClosed())
    <span class="text-danger">Закрыт</span>
@endif
