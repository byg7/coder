<?php
startblock('content');
echo <<<html
  <table>
    <tr>
      <th><form method="post" action="/group">
        <button type="submit" name="edit" value="__">New</button>
      </form></th>
      <th>Код</th>
      <th>Наименование</th>
      <th>Флаги</th>
      <th>Статей</th>
    </tr>
html;
if(isset($data['groups']))foreach($data['groups'] as $group){
  echo <<<html
    <tr>
      <td><form method="post" action="/group">
        <button type="submit" name="edit" value="{$group['group']}">Edit</button>
      </form></td>
      <td>{$group['group']}</td>
      <td>{$group['name']}</td>
      <td>{$group['flags']}</td>
      <td>{$group['count_pages']}</td>
    </tr>
html;
}
echo <<<html
  </table>
html;
endblock();
include template('layout');
