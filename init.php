<?php

OW::getRouter()->addRoute(new OW_Route('spdownload.category_index', 'download/category', "SPDOWNLOAD_CTRL_Category", 'index'));
OW::getRouter()->addRoute(new OW_Route('spdownload.category_update', 'download/category/:id/:name', "SPDOWNLOAD_CTRL_Category", 'index'));


OW::getRouter()->addRoute(new OW_Route('spdownload.index', 'download', "SPDOWNLOAD_CTRL_Download", 'index'));

OW::getRouter()->addRoute(new OW_Route('spdownload.upload_index', 'download/upload', "SPDOWNLOAD_CTRL_Upload", 'index'));
