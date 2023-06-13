<?php

declare(strict_types=1);

namespace jp\mcbe\fuyutsuki\Texter\text;

enum SendType: string {
	case ADD = "add";
	case EDIT = "edit";
	case MOVE = "move";
	case REMOVE = "remove";
}