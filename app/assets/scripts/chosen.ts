import 'chosen-js';
import 'chosen-js/chosen.css';

const init = (): void => {
  $('.chosen').chosen({ width: '100%', inherit_select_classes: true });
};

export default { init };
